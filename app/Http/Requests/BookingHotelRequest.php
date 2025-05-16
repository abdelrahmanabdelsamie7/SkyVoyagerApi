<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class BookingHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'user_id' => 'required|uuid|exists:users,id',
            'hotel_id' => 'required|uuid|exists:hotels,id',
            'num_of_rooms' => 'required|integer|min:1',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
        ];
    }
}
