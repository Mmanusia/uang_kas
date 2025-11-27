<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
        protected $fillable = [
        'user_id',
        'group_id',
        'month',
        'year',
        'limit_percentage',
        'limit_amount'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Groups::class);
    }
}
