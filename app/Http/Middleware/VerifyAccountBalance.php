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
        $accountId = $request->route('account');
        $amount = $request->input('amount');
        $account = Account::findOrFail($accountId);

        if ($operation == 'withdraw' && $account->currentBalance < $amount) {
            return response()->json(['message' => 'Insufficient funds'], 403);
        }

        return $next($request);
    }
}
