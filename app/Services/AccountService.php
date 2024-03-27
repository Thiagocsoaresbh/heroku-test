<?php

namespace App\Services;

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Exception;


class AccountService
{
    public function createAccount(array $accountData, User $user): Account
    {
        $accountData['user_id'] = $user->id;
        return Account::create($accountData);
    }

    public function listAccount(User $user)
    {
        $key = "account.{$user->id}";
        $seconds = 600; // Implementing cache for 10 minutes

        return Cache::remember($key, $seconds, function () use ($user) {
            return $user->account()->with('user')->first(); // Eager loading
        });
    }

    public function updateAccount(Account $account, array $accountData): Account
    {
        $account->update($accountData);
        return $account;
    }

    public function deleteAccount(Account $account): void
    {
        $account->delete();
    }

    public function deposit($accountId, $amount)
    {
        $account = Account::findOrFail($accountId);

        DB::transaction(function () use ($account, $amount) {
            $account->increment('currentBalance', $amount);
            $account->refresh();
            $account->transactions()->create([
                'type' => 'deposit',
                'amount' => $amount,
                'description' => 'Deposit made to the account',
                'transactionDate' => now(),
            ]);

            Cache::forget("account.{$account->user_id}");
        });

        // Retrive the fresh balance
        $freshBalance = $account->getFreshCurrentBalance();

        // Return the response
        return ['message' => 'Deposit successful', 'currentBalance' => $freshBalance];
    }

    public function withdraw(Request $request, $accountId)
    {
        $account = Account::findOrFail($accountId);
        $amount = $request->input('amount');

        return DB::transaction(function () use ($account, $amount) {
            if ($account->currentBalance < $amount) {
                return response()->json(['message' => 'Insufficient funds'], 400);
            }

            $account->decrement('currentBalance', $amount);

            $account->transactions()->create([
                'type' => 'withdrawal',
                'amount' => $amount,
                'description' => 'Withdrawal from the account',
                'transactionDate' => now(),
            ]);

            return response()->json(['message' => 'Withdrawal successful', 'balance' => $account->currentBalance]);
        });
    }

    public function transferMoney($fromAccountId, $toAccountId, $amount)
    {
        return DB::transaction(function () use ($fromAccountId, $toAccountId, $amount) {
            $fromAccount = Account::findOrFail($fromAccountId);
            $toAccount = Account::findOrFail($toAccountId);

            // Verify if the account has enough balance to transfer
            if ($fromAccount->currentBalance < $amount) {
                throw new Exception('Insufficient funds.');
            }

            // Update the balance of both accounts accordingly
            $fromAccount->decrement('currentBalance', $amount);
            $toAccount->increment('currentBalance', $amount);

            // Create transactions for both accounts
            $fromAccount->transactions()->create([
                'type' => 'transfer_out',
                'amount' => $amount,
                'description' => "Transfer to account {$toAccountId}",
                'transactionDate' => now(),
            ]);

            $toAccount->transactions()->create([
                'type' => 'transfer_in',
                'amount' => $amount,
                'description' => "Transfer from account {$fromAccountId}",
                'transactionDate' => now(),
            ]);

            return ['success' => true, 'message' => 'Transfer successful'];
        });
    }
}
