<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'photo_path',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
