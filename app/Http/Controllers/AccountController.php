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
        $this->middleware('auth:sanctum')->except(['getBalance']);
        $this->accountService = $accountService;
    }

    // Ajuste para listar a conta única do usuário
    public function index(Request $request)
    {
        $account = $this->accountService->listAccount(Auth::user());
        return response()->json($account);
    }

    // Ajuste para impedir a criação de múltiplas contas por usuário
    public function store(Request $request)
    {
        if (Auth::user()->account) {
            return response()->json(['error' => 'User already has an account'], 403);
        }

        $validatedData = $request->validate([
            'accountNumber' => 'required|unique:accounts,accountNumber',
            'currentBalance' => 'required|numeric|min:0',
        ]);

        $account = $this->accountService->createAccount($validatedData, Auth::user());
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
        $validated = $request->validate([
            'fromAccountId' => 'required|exists:account,id',
            'toAccountId' => 'required|exists:account,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $result = $this->accountService->transferMoney($validated['fromAccountId'], $validated['toAccountId'], $validated['amount'], $request->user());

        if ($result['success']) {
            return response()->json(['message' => $result['message']], 201);
        } else {
            return response()->json(['error' => $result['message']], 400);
        }
    }

    public function deposit(Request $request, $accountId)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Log::info('Deposit request', ['user_id' => $request->user()->id, 'account_id' => $accountId, 'amount' => $request->input('amount')]);

        return $this->accountService->deposit($request, $accountId);
    }

    public function getBalance(Request $request, $accountId)
    {
        $account = $request->user()->accounts()->findOrFail($accountId);
        return response()->json(['currentBalance' => $account->currentBalance]);
    }
}
