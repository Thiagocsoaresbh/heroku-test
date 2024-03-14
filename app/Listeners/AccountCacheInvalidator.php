<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Cache;

class AccountCacheInvalidator
{
    public function handle($event)
    {
        Cache::forget('accounts.' . $event->account->user_id);
    }
}
