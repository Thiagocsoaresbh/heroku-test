<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Services\AccountService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->middleware('auth:sanctum');
        $this->accountService = $accountService;
    }

    public function index(Request $request)
    {
        $account = $request->user()->account;
        return response()->json($account);
    }

    public function store(Request $request)
    {
        if ($request->user()->account()->exists()) {
            return response()->json(['error' => 'User already has an account'], 422);
        }

        $validatedData = $request->validate([
            'accountNumber' => 'required|unique:accounts,accountNumber',
            'currentBalance' => 'required|numeric|min:0',
        ]);

        $account = $this->accountService->createAccount($validatedData, $request->user());
        return response()->json($account, 201);
    }

    public function show(Account $account)
    {
        $this->authorize('view', $account);
        return response()->json($account->load('user'));
    }

    public function update(Request $request, Account $account)
    {
        $this->authorize('update', $account);

        $validatedData = $request->validate([
            'currentBalance' => 'sometimes|required|numeric|min:0',
        ]);

        $updatedAccount = $this->accountService->updateAccount($account, $validatedData);
        return response()->json($updatedAccount);
    }

    public function destroy(Account $account)
    {
        $this->authorize('delete', $account);

        $this->accountService->deleteAccount($account);
        return response()->json(null, 204);
    }

    public function transfer(Request $request)
    {
        $userAccount = $request->user()->account;
        if (!$userAccount) {
            return response()->json(['error' => 'No account found for user'], 404);
        }

        $validated = $request->validate([
            'toAccountId' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $result = $this->accountService->transferMoney($userAccount->id, $validated['toAccountId'], $validated['amount'], $request->user());

        return $result['success']
            ? response()->json(['message' => $result['message']], 201)
            : response()->json(['error' => $result['message']], 400);
    }

    public function deposit(Request $request)
    {
        $userAccount = $request->user()->account;
        if (!$userAccount) {
            return response()->json(['error' => 'No account found for user'], 404);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        return $this->accountService->deposit($request, $userAccount->id);
    }

    public function withdraw(Request $request)
    {
        $userAccount = $request->user()->account;
        if (!$userAccount) {
            return response()->json(['error' => 'No account found for user'], 404);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        return $this->accountService->withdraw($request, $userAccount->id);
    }

    public function getBalance(Request $request)
    {
        $account = $request->user()->account;
        if (!$account) {
            return response()->json(['error' => 'No account found for user'], 404);
        }

        return response()->json(['currentBalance' => $account->currentBalance]);
    }
}
