<?php

namespace App\Services;

use App\Models\Account;
use App\Models\User;

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
}
