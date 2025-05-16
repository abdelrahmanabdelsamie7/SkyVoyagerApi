<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class HotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'image_cover' => 'required|image|mimes:png,jpg,jpeg,webp',
            'price_per_night' => 'required|numeric|min:0',
            'dinner_option' => 'boolean',
            'ac_option' => 'boolean',
            'hot_tub_option' => 'boolean',
            'num_of_beds' => 'required|integer|min:1',
            'location' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
}