<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // Using the 'with' method to eager load the 'account' relationship
        $transactions = Transaction::with('account.user')->get(); // If need access to the user of the account, we can use the 'account.user' relationship
        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:income,expense,deposit',
            'amount' => 'required|numeric|min:0.01', // The amount must be a positive number
            'description' => 'nullable|string|max:255',
            'transactionDate' => 'required|date',
        ]);

        $transaction = Transaction::create($validatedData);

        return response()->json($transaction, 201);
    }

    public function show(Transaction $transaction)
    {
        //Carrying out eager loading for the 'account' relationship to optimize the query
        return $transaction->load('account');
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validatedData = $request->validate([
            'amount' => 'sometimes|required|numeric|min:0.01', // The amount must be a positive number too when updating
            'description' => 'nullable|string|max:255',
            'transactionDate' => 'sometimes|required|date',
        ]); // The 'account_ id' and 'type' fields are not allowed to be updated

        $transaction->update($validatedData); // Updatin the transaction

        return response()->json($transaction);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return response()->json(null, 204);
    }
<<<<<<< HEAD

    public function incomes()
    {
        $transactions = Transaction::with('account')
            ->where('type', '=', 'income')
            ->get();
        return response()->json($transactions);
    }

    public function expenses()
    {
        $transactions = Transaction::with('account')
            ->where('type', '=', 'expense')
            ->get();
        return response()->json($transactions);
    }
=======
<<<<<<< Updated upstream
=======

    public function incomes(Request $request)
    {
        $userId = $request->user()->id;
        $incomes = Transaction::where('user_id', $userId)->where('type', 'income')->get();
        return response()->json($incomes);
    }

    public function expenses(Request $request)
    {
        $userId = $request->user()->id;
        $expenses = Transaction::where('user_id', $userId)->where('type', 'expense')->get();
        return response()->json($expenses);
    }
>>>>>>> Stashed changes
>>>>>>> dev
}
