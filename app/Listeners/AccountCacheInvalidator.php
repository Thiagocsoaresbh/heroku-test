<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Cache;

class AccountCacheInvalidator
{
    public function handle($event)
    {
        if ($event->account && $event->account->user_id) {
            Cache::forget('account.' . $event->account->user_id);
        }
    }
}
