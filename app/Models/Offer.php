<?php
namespace App\Models;
use App\Models\{User,FlightSchedule,OfferImage};
use App\traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory, UsesUuid;
    protected $table = 'offers';
    protected $fillable = [
        'title',
        'image_cover',
        'num_of_tickets',
        'from_date',
        'to_date',
        'city',
        'price_per_ticket',
        'description',
        'terms_and_conditions'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'booking_offers')
            ->withPivot('num_of_tickets')
            ->withTimestamps();
    }
    public function flightSchedules()
    {
        return $this->hasMany(FlightSchedule::class)
            ->select([
                'id',
                'offer_id',
                'departure_city',
                'departure_time',
                'arrival_city',
                'arrival_time',
                'price_multiplier',
                'created_at',
                'updated_at'
            ])
            ->with('offer:id,price_per_ticket');
    }
    public function getTotalPriceAttribute()
    {
        return $this->pivot?->num_of_tickets * $this->price_per_ticket ?? 0;
    }
    public function images()
    {
        return $this->hasMany(OfferImage::class, 'offer_id');
    }
}