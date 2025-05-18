<?php
namespace App\Models;
use App\Models\Offer;
use App\traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfferImage extends Model
{
    use HasFactory, UsesUuid;
    protected $table = 'offer_images';
    protected $fillable = ['offer_id', 'image'];
    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }
}