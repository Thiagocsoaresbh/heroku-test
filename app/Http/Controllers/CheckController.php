<?php

namespace App\Http\Controllers;

use App\Models\Check;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    public function index()
    {
        return Check::with('account')->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'imagePath' => 'required|string',
            'status' => 'required|in:pending,accepted,rejected',
            'submissionDate' => 'required|date',
        ]);

        $check = Check::create($validatedData);

        return response()->json($check, 201);
    }

    public function show(Check $check)
    {
        return $check->load('account');
    }

    public function update(Request $request, Check $check)
    {
        $validatedData = $request->validate([
            'status' => 'sometimes|required|in:pending,accepted,rejected',
        ]);

        $check->update($validatedData);

        return response()->json($check);
    }

    public function destroy(Check $check)
    {
        $check->delete();

        return response()->json(null, 204);
    }
}
