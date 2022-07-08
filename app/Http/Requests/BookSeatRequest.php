<?php

namespace App\Http\Requests;

use App\Models\Seat;
use Illuminate\Foundation\Http\FormRequest;

class BookSeatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'seat_id' => 'required|exists:seats,id',
            'departure_city_id' => 'required|exists:cities,id',
            'destination_city_id' => 'required|exists:cities,id',
        ];
    }
}
