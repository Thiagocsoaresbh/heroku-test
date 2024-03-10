<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountService
{
    public function createAccount(array $accountData): Account
    {
        return Account::create($accountData);
    }

    public function listAccounts()
    {
        return Account::with('user')->get();
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

    public function transferMoney($fromAccountId, $toAccountId, $amount)
    {
        DB::beginTransaction();
        try {
            $fromAccount = Account::findOrFail($fromAccountId);
            $toAccount = Account::findOrFail($toAccountId);

            if ($fromAccount->currentBalance < $amount) {
                return ['success' => false, 'message' => 'Saldo insuficiente'];
            }

            $fromAccount->currentBalance -= $amount;
            $fromAccount->save();

            $toAccount->currentBalance += $amount;
            $toAccount->save();

            DB::commit();
            return ['success' => true, 'message' => 'Transferência realizada com sucesso'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Erro ao realizar transferência: ' . $e->getMessage()];
        }
    }
}
