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

    public function transferMoney($fromAccountId, $toAccountId, $amount, User $user)
    {
        if ($fromAccount = Account::find($fromAccountId)) {
            if ($fromAccount->user_id !== $user->id) {
                return ['success' => false, 'message' => 'User does not have permission to perform this transfer'];
            }
        } else {
            return ['success' => false, 'message' => 'Source account not found'];
        }

        DB::beginTransaction();
        try {
            $toAccount = Account::findOrFail($toAccountId);

            if ($fromAccount->currentBalance < $amount) {
                return ['success' => false, 'message' => 'Insufficient funds'];
            }

            $fromAccount->currentBalance -= $amount;
            $fromAccount->save();

            $toAccount->currentBalance += $amount;
            $toAccount->save();

            DB::commit();
            return ['success' => true, 'message' => 'Sucess transfer'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Error to realize transfer'];
        }
    }
}
