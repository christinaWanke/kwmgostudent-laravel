<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'persnum', 'firstName', 'lastName', 'email', 'password', 'description', 'semester', 'isTutor'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function slots() : BelongsToMany{
        return $this->belongsToMany(Slot::class)->withTimestamps();
    }

    public function course() : HasMany{
        return $this->hasMany(Course::class);
    }

    public function comments():HasMany{
        return $this->hasMany(Comment::class);
    }

    /*public function slotBooked() : HasMany {
        return $this->hasMany(SlotUser::class);
    }*/
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['user' => [
            'id' => $this->id,
            'persnum' => $this->persnum,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'description' => $this->description,
            'isTutor' => $this->isTutor,
            'semester' => $this->semester
            ]
        ];
    }
}
