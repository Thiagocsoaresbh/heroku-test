<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Services\AccountService;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function index()
    {
        $accounts = $this->accountService->listAccounts();
        return response()->json($accounts);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'accountNumber' => 'required|unique:accounts',
            'currentBalance' => 'required|numeric|min:0',
        ]);

        $account = $this->accountService->createAccount($validatedData);
        return response()->json($account, 201);
    }

    public function show(Account $account)
    {
        return response()->json($account->load('user'));
    }

    public function update(Request $request, Account $account)
    {
        $validatedData = $request->validate([
            'currentBalance' => 'sometimes|required|numeric|min:0',
        ]);

        $updatedAccount = $this->accountService->updateAccount($account, $validatedData);
        return response()->json($updatedAccount);
    }

    public function destroy(Account $account)
    {
        $this->accountService->deleteAccount($account);
        return response()->json(null, 204);
    }

    public function transfer(Request $request)
    {
        $validated = $request->validate([
            'fromAccountId' => 'required|exists:accounts,id',
            'toAccountId' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $result = $this->accountService->transferMoney($validated['fromAccountId'], $validated['toAccountId'], $validated['amount']);

        if ($result['success']) {
            return response()->json($result['message'], 200);
        } else {
            return response()->json(['error' => $result['message']], 400);
        }
    }
}
