<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class OfferRequest extends FormRequest
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
            'num_of_tickets' => 'required|integer|min:1',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'city' => 'required|string',
            'price_per_ticket' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
        ];
    }
}
