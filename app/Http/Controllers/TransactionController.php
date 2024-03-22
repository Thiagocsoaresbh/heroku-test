<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // list all transactions for the authenticated user
    public function index(Request $request)
    {
        $userAccount = $request->user()->account;
        if (!$userAccount) {
            return response()->json(['error' => 'No account found for user'], 404);
        }

        $transactions = $userAccount->transactions;
        return response()->json($transactions);
    }

    // Store a new transaction to the authenticated user's account
    public function store(Request $request)
    {
        $userAccount = $request->user()->account;
        if (!$userAccount) {
            return response()->json(['error' => 'No account found for user'], 404);
        }

        $validatedData = $request->validate([
            'type' => 'required|in:income,expense,deposit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'transactionDate' => 'required|date',
        ]);

        $transaction = $userAccount->transactions()->create($validatedData);
        return response()->json($transaction, 201);
    }

    public function show($id)
    {
        $userAccount = Auth::user()->account;
        if (!$userAccount) {
            return response()->json(['error' => 'No account found for user'], 404);
        }

        $transaction = $userAccount->transactions()->find($id);
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        return response()->json($transaction);
    }

    public function update(Request $request, $id)
    {
        $userAccount = Auth::user()->account;
        if (!$userAccount) {
            return response()->json(['error' => 'No account found for user'], 404);
        }

        $transaction = $userAccount->transactions()->find($id);
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        $validatedData = $request->validate([
            'amount' => 'sometimes|required|numeric|min:0.01',
            'description' => 'nullable|string',
            'transactionDate' => 'sometimes|required|date',
        ]);

        $transaction->update($validatedData);
        return response()->json($transaction);
    }

    public function destroy($id)
    {
        $userAccount = Auth::user()->account;
        if (!$userAccount) {
            return response()->json(['error' => 'No account found for user'], 404);
        }

        $transaction = $userAccount->transactions()->find($id);
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted successfully']);
    }

    public function incomes(Request $request)
    {
        $userAccount = $request->user()->account;
        if (!$userAccount) {
            return response()->json(['error' => 'No account found for user'], 404);
        }

        $incomes = $userAccount->transactions()->where('type', 'income')->get();
        return response()->json($incomes);
    }

    public function expenses(Request $request)
    {
        $userAccount = $request->user()->account;
        if (!$userAccount) {
            return response()->json(['error' => 'No account found for user'], 404);
        }

        $expenses = $userAccount->transactions()->where('type', 'expense')->get();
        return response()->json($expenses);
    }
}
