<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'type',
        'group_id',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function groups()
    {
        return $this->belongsTo(Groups::class);
    }
}
