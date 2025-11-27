<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyIncome extends Model
{
    protected $fillable = [
        'user_id',
        'year',
        'month',
        'amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
