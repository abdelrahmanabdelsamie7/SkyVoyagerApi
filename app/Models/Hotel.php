<?php
namespace App\Models;
use App\Models\User;
use App\traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotel extends Model
{
    use HasFactory, UsesUuid;
    protected $table = 'hotels';
    protected $fillable = [
        'title',
        'image_cover',
        'price_per_night',
        'dinner_option',
        'ac_option',
        'hot_tub_option',
        'num_of_beds',
        'description',
        'location'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'booking_hotels')
            ->withPivot('num_of_rooms', 'check_in_date', 'check_out_date')
            ->withTimestamps();
    }
}
