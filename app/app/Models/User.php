<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Services\MailService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'role',
        'is_banned'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }


    public function getProfile()
    {
        return $this->profile()->with('photos')->firstOrFail();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function getUserName(int $id): ?string
    {
        $user = self::find($id);

        return $user ? $user->name : null;
    }

    public function sendPasswordResetNotification($token)
    {
        app(MailService::class)->sendResetPasswordEmail($this, $token);
    }

    public static function isAdmin(): bool
    {
        $user = auth()->user();
        return $user && $user->role === 'admin';
    }

    public static function isBanned(): bool
    {
        $user = auth()->user();
        return $user && $user->is_banned;
    }


}
