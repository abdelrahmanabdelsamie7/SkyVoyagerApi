<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class PopularPlaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'best_season_to_visit' => 'required|string',
            'image_cover' => 'required|image|mimes:png,jpg,jpeg,webp',
            'price_of_ticket' => 'required|numeric|min:0',
        ];
    }
}