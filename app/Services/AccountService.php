<?php

namespace App\Services;

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

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
        $seconds = 300; // Implementing cache for 5 minutes

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

    public function deposit(Request $request, $accountId)
    {
        $account = Account::findOrFail($accountId);
        $amount = $request->input('amount');

        DB::transaction(function () use ($account, $amount) {
            $account->increment('currentBalance', $amount);
            $account->transactions()->create([
                'type' => 'deposit',
                'amount' => $amount,
                'description' => 'Deposit made to the account',
                'transactionDate' => now(),
            ]);
        });

        return response()->json(['message' => 'Deposit successful', 'currentBalance' => $account->currentBalance], 200);
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

    public function transferMoney($fromAccountId, $toAccountId, $amount, $user)
    {
        DB::beginTransaction();
        try {
            $fromAccount = Account::findOrFail($fromAccountId);
            $toAccount = Account::findOrFail($toAccountId);

            // Verify suficient balance
            if ($fromAccount->currentBalance < $amount) {
                DB::rollBack();
                return ['success' => false, 'message' => 'Insufficient funds'];
            }

            // Remove the origin balance and put on destiny account
            $fromAccount->currentBalance -= $amount;
            $fromAccount->save();

            $toAccount->currentBalance += $amount;
            $toAccount->save();

            DB::commit();
            return ['success' => true, 'message' => 'Transfer successful'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Error processing transfer'];
        }
    }
}
