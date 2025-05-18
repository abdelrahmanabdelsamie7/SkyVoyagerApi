<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class OfferImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'offer_id' => 'required|uuid|exists:offers,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ];
    }
}
