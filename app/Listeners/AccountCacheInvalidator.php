<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Cache;

class AccountCacheInvalidator
{
    public function handle($event)
    {
        if ($event->account && $event->account->user_id) {
            Cache::forget('accounts.' . $event->account->user_id);
        }
    }
}
