<?php

namespace App\Http\Requests;

use App\TripStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $trip = $this->route('trip');
        return $this->user()->can('update', $trip);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $trip = $this->route('trip');
        $user = $this->user();

        $rules = [
            'trip_number' => ['sometimes', 'string', 'max:255', Rule::unique('trips', 'trip_number')->ignore($trip->id)],
            'vehicle_id' => ['sometimes', 'exists:vehicles,id'],
            'driver_id' => ['sometimes', 'exists:users,id'],
            'a_code' => ['nullable', 'string', 'max:255'],
            'destination_from' => ['sometimes', 'string', 'max:255'],
            'destination_to' => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', Rule::enum(TripStatus::class)],
            'mileage' => ['nullable', 'numeric', 'min:0'],
            'driver_description' => ['nullable', 'string'],
            'admin_description' => ['nullable', 'string'],
            'trip_date' => ['sometimes', 'date'],
            'invoice_number' => ['nullable', 'string', 'max:255'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'stops' => ['nullable', 'array'],
            'stops.*.destination' => ['required_with:stops', 'string', 'max:255'],
            'stops.*.stop_order' => ['required_with:stops', 'integer', 'min:1'],
            'stops.*.notes' => ['nullable', 'string'],
        ];

        // Drivers can only update status
        if ($user->isDriver()) {
            return [
                'status' => ['required', Rule::enum(TripStatus::class)],
            ];
        }

        return $rules;
    }
}
