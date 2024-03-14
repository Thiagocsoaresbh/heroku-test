<?php

namespace App\Http\Controllers;

use App\Models\Check;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function listChecks()
    {
        $checks = Check::where('status', 'pending')->get();
        return response()->json($checks);
    }

    public function approveCheck(Request $request, $checkId)
    {
        $check = Check::findOrFail($checkId);
        // Verify if the user has permission to approve the check
        $this->authorize('approve', $check);

        $check->status = 'accepted';
        $check->save();

        return response()->json(['message' => 'Check approved successfully']);
    }

    public function rejectCheck(Request $request, $checkId)
    {
        $check = Check::findOrFail($checkId);
        // Verify if the user has permission to reject the check
        $this->authorize('reject', $check);

        $check->status = 'rejected';
        $check->save();

        return response()->json(['message' => 'Check rejected successfully']);
    }
}
