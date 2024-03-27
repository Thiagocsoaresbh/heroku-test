<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Account;

class VerifyAccountBalance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $operation
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $operation = 'withdraw')
    {
        $accountId = $request->input('accountId'); // For operations like deposit, the account ID is passed in the request

        if ($operation === 'transfer') {
            $accountId = $request->input('fromAccountId'); // To transfer, the account ID is the from account
        } elseif ($operation === 'withdraw') {
            $accountId = $request->user()->account->id; // To withdraw, the account ID is the authenticated user's account
        }

        if (!$accountId) {
            return response()->json(['message' => 'Account ID is missing'], 400);
        }

        $account = Account::findOrFail($accountId);

        if ($account->currentBalance < $request->input('amount')) {
            return response()->json(['message' => 'Insufficient funds'], 403);
        }

        return $next($request);
    }
}
