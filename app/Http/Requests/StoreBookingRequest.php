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
            'booking_time'  => ['required', 'string', 'in:08:00,09:00,10:00,11:00,12:00,13:00,14:00,15:00,16:00,17:00'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email'=> ['required', 'email', 'max:255'],
            'customer_phone'=> ['required', 'string', 'max:20'],
            'vehicle_plate' => ['required', 'string', 'max:20'],
            'vehicle_model' => ['required', 'string', 'max:255'],
            'notes'         => ['nullable', 'string', 'max:1000'],
        ];
    }
}
