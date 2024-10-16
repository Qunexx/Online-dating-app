<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'blocker_id',
        'blocked_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
