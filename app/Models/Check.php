<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    use HasFactory;

    protected $fillable = ['account_id', 'amount', 'description', 'imagePath', 'status', 'submissionDate'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
