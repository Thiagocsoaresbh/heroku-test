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

    public function listAccounts(User $user)
    {
        $key = "accounts.{$user->id}";
        $seconds = 600; // Implementing cache for 10 minutes

        return Cache::remember($key, $seconds, function () use ($user) {
            return $user->accounts()->with('user')->get(); // Eager loading
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
        // Validating and logic for deposit
        $amount = $request->input('amount');
        $account->currentBalance += $amount;
        $account->save();

        return response()->json(['message' => 'Deposit successful', 'balance' => $account->currentBalance]);
    }

    public function withdraw(Request $request, $accountId)
    {
        $account = Account::findOrFail($accountId);
        // Validating and logic for withdraw
        $amount = $request->input('amount');
        // Verify suficient balance
        if ($account->currentBalance >= $amount) {
            $account->currentBalance -= $amount;
            $account->save();
            return response()->json(['message' => 'Withdrawal successful', 'balance' => $account->currentBalance]);
        } else {
            return response()->json(['message' => 'Insufficient funds'], 400);
        }
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
