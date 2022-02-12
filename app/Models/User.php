<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Mail\Mailable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public const LOCK = 'lock';
    public const UNLOCK = 'unlock';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_name',
        'department_id',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'password',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function department(): belongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function role(): belongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function isAdmin(): bool
    {
        return $this->role->id === Role::ADMIN;
    }

    public function isModerator(): bool
    {
        return $this->role->id === Role::MODERATOR;
    }

    public function tests(): belongsToMany
    {
        return $this->belongsToMany(Test::class)
            ->withPivot('result', 'attempt');
    }

    public function sendPasswordResetNotification($token)
    {
        $email = $this->getEmailForPasswordReset();

        Mail::to($this->getEmailForPasswordReset())
            ->send(new class($token, $email) extends Mailable{
            private string $token;
            private string $email;

            public function __construct($token, $email)
            {
                $this->token = $token;
                $this->email = $email;
            }

            public function build()
            {
                return $this
                    ->subject(__('auth.password_subject'))
                    ->view('auth.reset-email', [
                        'token' => $this->token,
                        'email' => $this->email
                    ]);
            }
        });
    }
}
