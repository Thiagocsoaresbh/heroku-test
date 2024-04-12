<?php

namespace App\Http\Controllers;

use App\Models\Check;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function listChecks()
    {
        if (!Gate::allows('isAdmin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $checks = Check::where('status', 'pending')->get();
        return response()->json($checks);
    }

    public function approveCheck(Request $request, $checkId)
    {
        if (!Gate::allows('isAdmin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $check = Check::findOrFail($checkId);
        $check->status = 'accepted';
        $check->save();

        return response()->json(['message' => 'Check accepted successfully']);
    }

    public function rejectCheck(Request $request, $checkId)
    {
        if (!Gate::allows('isAdmin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $check = Check::findOrFail($checkId);
        $check->status = 'rejected';
        $check->save();

        return response()->json(['message' => 'Check rejected successfully']);
    }
}
