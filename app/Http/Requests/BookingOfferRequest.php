<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class BookingOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'user_id' => 'required|uuid|exists:users,id',
            'offer_id' => 'required|uuid|exists:offers,id',
            'num_of_tickets' => 'required|integer|min:1',
        ];
    }
}