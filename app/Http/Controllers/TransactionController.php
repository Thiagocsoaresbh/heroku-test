<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return Transaction::with('account')->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:income,expense,deposit',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'transactionDate' => 'required|date',
        ]);

        $transaction = Transaction::create($validatedData);

        return response()->json($transaction, 201);
    }

    public function show(Transaction $transaction)
    {
        return $transaction->load('account');
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Note: Be careful with allowing updates on transactions, consider business logic.
        $validatedData = $request->validate([
            'amount' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'transactionDate' => 'sometimes|required|date',
        ]);

        $transaction->update($validatedData);

        return response()->json($transaction);
    }

    public function destroy(Transaction $transaction)
    {
        // Consider if you should allow deleting transactions or how it affects account balance.
        $transaction->delete();

        return response()->json(null, 204);
    }
}
