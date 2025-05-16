<?php
namespace App\Models;
use App\Models\Offer;
use App\traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlightSchedule extends Model
{
    use HasFactory, UsesUuid;
    protected $table = 'flight_schedules';
    protected $fillable = [
        'offer_id',
        'departure_city',
        'departure_time',
        'arrival_city',
        'arrival_time',
        'price_multiplier'
    ];
    protected $appends = ['calculated_price'];
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
    public function getCalculatedPriceAttribute()
    {
        return $this->offer->price_per_ticket * $this->price_multiplier;
    }
}