<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class FlightScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'offer_id' => 'required|exists:offers,id',
            'departure_city' => 'required|string',
            'departure_time' => 'required|date_format:H:i',
            'arrival_city' => 'nullable|string',
            'arrival_time' => 'nullable|date_format:H:i',
            'price_multiplier' => 'required|numeric|min:0|max:99.99',
        ];
    }
}
