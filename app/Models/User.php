<?php
namespace App\Models;
use App\Models\{Offer,Hotel};
use App\traits\UsesUuid;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, UsesUuid;
    protected $fillable = [
        'name',
        'email',
        'password',
        'verification_token',
        'verification_token_expires_at',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'booking_offers')
            ->withPivot('num_of_tickets')
            ->withTimestamps();
    }
    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'booking_hotels')
            ->withPivot('num_of_rooms', 'check_in_date', 'check_out_date')
            ->withTimestamps();
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}