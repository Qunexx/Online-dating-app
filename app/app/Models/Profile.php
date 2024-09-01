<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'gender',
        'birthdate',
        'location',
        'interests',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function photos()
    {
        return $this->hasMany(ProfilePhoto::class);
    }

}
