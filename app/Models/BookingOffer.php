<?php
namespace App\Models;
use App\Models\{Offer,User};
use App\traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingOffer extends Model
{
    use HasFactory , UsesUuid;
    protected $table = 'booking_offers';
    protected $fillable = [
        'user_id',
        'offer_id',
        'num_of_tickets'
    ];
    public function getTotalPriceAttribute()
    {
        if (!$this->offer) return 0;
        return $this->num_of_tickets * $this->offer->price_per_ticket;
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