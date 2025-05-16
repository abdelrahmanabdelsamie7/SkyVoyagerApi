<?php
namespace App\Models;
use App\Models\{Hotel,User};
use App\traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingHotel extends Model
{
    use HasFactory, UsesUuid;
    protected $table = 'booking_hotels';
    protected $fillable = [
        'user_id',
        'hotel_id',
        'num_of_rooms',
        'check_in_date',
        'check_out_date'
    ];
    public function getNumberOfNightsAttribute()
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }
    public function getTotalPriceAttribute()
    {
        $hotel = $this->hotel;
        $nights = $this->number_of_nights;
        return $this->num_of_rooms * $nights * $hotel->price_per_night;
    }
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
