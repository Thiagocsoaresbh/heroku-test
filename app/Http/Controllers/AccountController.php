<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        return Account::with('user')->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'accountNumber' => 'required|unique:accounts',
            'currentBalance' => 'required|numeric|min:0',
        ]);

        $account = Account::create($validatedData);

        return response()->json($account, 201);
    }

    public function show(Account $account)
    {
        return $account->load('user');
    }

    public function update(Request $request, Account $account)
    {
        $validatedData = $request->validate([
            'currentBalance' => 'sometimes|required|numeric|min:0',
        ]);

        $account->update($validatedData);

        return response()->json($account);
    }

    public function destroy(Account $account)
    {
        $account->delete();

        return response()->json(null, 204);
    }
}
