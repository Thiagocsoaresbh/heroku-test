<?php

namespace App\Services;

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AccountService
{
    public function createAccount(array $accountData, User $user): Account
    {
        $accountData['user_id'] = $user->id;
        return Account::create($accountData);
    }

    public function listAccounts(User $user)
    {
        return $user->accounts()->with('user')->get();
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
