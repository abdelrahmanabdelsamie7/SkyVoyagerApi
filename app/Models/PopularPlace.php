<?php
namespace App\Models;
use App\traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PopularPlace extends Model
{
    use HasFactory, UsesUuid;
    protected $table = 'popular_places';
    protected $fillable = [
        'title',
        'image_cover',
        'price_of_ticket',
        'best_season_to_visit',
        'description',
        'location'
    ];
}