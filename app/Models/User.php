<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['username', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [];

    /**
     * User has one account.
     */
    public function account()
    {
        return $this->hasOne(Account::class);
    }

    /**
     * Verify if the user has an account.
     * 
     * @return bool
     */
    public function hasAccount(): bool
    {
        return $this->account()->exists();
    }

    /**
     * Return the first account of the user.
     *
     * @return Account|null
     */
    public function firstAccount()
    {
        // Assuming each user only has one account, we can use first() to get it.
        return $this->account()->first();
    }
}
