<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\{Hotel, User};
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
        'num_of_beds',
        'check_in_date',
        'check_out_date'
    ];

    protected $dates = ['check_in_date', 'check_out_date'];
    public function getNumberOfNightsAttribute()
    {
        if (!$this->check_in_date || !$this->check_out_date) {
            return 0;
        }
        $checkInDate = Carbon::parse($this->check_in_date);
        $checkOutDate = Carbon::parse($this->check_out_date);
        if ($checkOutDate->lte($checkInDate)) {
            return 0;
        }
        return $checkInDate->diffInDays($checkOutDate);
    }
    public function getTotalPriceAttribute()
    {
        $hotel = $this->hotel;
        $nights = $this->number_of_nights;

        // تأكيد وجود الفندق والسعر لكل ليلة
        if (!$hotel || !$hotel->price_per_night) {
            return 0;
        }

        return $this->num_of_beds * $nights * (float) $hotel->price_per_night;
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