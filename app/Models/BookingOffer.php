<?php
namespace App\Models;
use App\Models\{Offer, User, FlightSchedule};
use App\traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingOffer extends Model
{
    use HasFactory, UsesUuid;
    protected $table = 'booking_offers';
    protected $fillable = [
        'user_id',
        'schedule_id',
        'num_of_tickets'
    ];

    public function getTotalPriceAttribute()
    {
        if (!$this->schedule || !$this->schedule->offer)
            return 0;
        return $this->num_of_tickets * $this->schedule->offer->price_per_ticket;
    }

    public function schedule()
    {
        return $this->belongsTo(FlightSchedule::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
