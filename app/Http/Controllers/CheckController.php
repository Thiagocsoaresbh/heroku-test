<?php

namespace App\Http\Controllers;

use App\Models\Check;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckController extends Controller
{
    public function index()
    {
        // Searching for checks that belong to the authenticated user
        $user = Auth::user();
        $checks = Check::where('account_id', $user->account->id)->get();
        return response()->json($checks);
    }

    public function store(Request $request)
    {
        // Create a new check for the authenticated user
        $user = Auth::user();
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'imagePath' => 'required|string',
            'status' => 'required|in:pending,accepted,rejected',
            'submissionDate' => 'required|date',
        ]);
        $validatedData['account_id'] = $user->account->id;

        $check = Check::create($validatedData);
        return response()->json($check, 201);
    }

    public function show($id)
    {
        $user = Auth::user();
        $check = Check::where('id', $id)->where('account_id', $user->account->id)->firstOrFail();
        return response()->json($check);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $check = Check::where('id', $id)->where('account_id', $user->account->id)->firstOrFail();

        $validatedData = $request->validate([
            'amount' => 'sometimes|required|numeric|min:0',
            'description' => 'sometimes|nullable|string',
            'imagePath' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,accepted,rejected',
            'submissionDate' => 'sometimes|required|date',
        ]);

        $check->update($validatedData);
        return response()->json($check);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $check = Check::where('id', $id)->where('account_id', $user->account->id)->firstOrFail();
        $check->delete();
        return response()->json(null, 204);
    }

    public function approveCheck($id)
    {
        $user = Auth::user();
        if ($user->role !== 'administrator') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $check = Check::where('id', $id)->where('account_id', $user->account->id)->firstOrFail();
        $check->status = 'accepted';
        $check->save();
        return response()->json(['message' => 'Check accepted successfully']);
    }

    public function rejectCheck($id)
    {
        $user = Auth::user();
        if ($user->role !== 'administrator') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $check = Check::where('id', $id)->where('account_id', $user->account->id)->firstOrFail();
        $check->status = 'rejected';
        $check->save();
        return response()->json(['message' => 'Check rejected successfully']);
    }

    public function checksByStatus($status)
    {
        $checks = Check::where('status', $status)->get();
        return response()->json($checks);
    }
}
