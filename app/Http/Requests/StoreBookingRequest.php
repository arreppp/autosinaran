<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'package_id'    => ['required', 'exists:packages,id'],
            'booking_date'  => ['required', 'date', 'after_or_equal:today'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email'=> ['required', 'email', 'max:255'],
            'customer_phone'=> ['required', 'string', 'max:20'],
            'vehicle_plate' => ['required', 'string', 'max:20'],
            'vehicle_model' => ['required', 'string', 'max:255'],
            'notes'         => ['nullable', 'string', 'max:1000'],
        ];
    }
}
