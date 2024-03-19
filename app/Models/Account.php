<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'accountNumber', 'currentBalance'];

    public static function boot()
    {
        parent::boot();

        static::created(function ($account) {
            Event::dispatch('account.created', $account);
        });

        static::updated(function ($account) {
            Event::dispatch('account.updated', $account);
        });

        static::deleted(function ($account) {
            Event::dispatch('account.deleted', $account);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function checks()
    {
        return $this->hasMany(Check::class);
    }

    public function getCurrentBalanceAttribute()
    {
        return $this->transactions->reduce(function ($carry, $transaction) {
            return $transaction->type === 'deposit' ? $carry + $transaction->amount : $carry - $transaction->amount;
        }, 0);
    }
}
