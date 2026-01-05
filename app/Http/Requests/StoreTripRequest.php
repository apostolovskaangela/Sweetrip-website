<?php

namespace App\Http\Requests;

use App\TripStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Trip::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trip_number' => ['required', 'string', 'max:255', 'unique:trips,trip_number'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'driver_id' => ['required', 'exists:users,id'],
            'a_code' => ['nullable', 'string', 'max:255'],
            'destination_from' => ['required', 'string', 'max:255'],
            'destination_to' => ['required', 'string', 'max:255'],
            'status' => ['nullable', Rule::enum(TripStatus::class)],
            'mileage' => ['nullable', 'numeric', 'min:0'],
            'driver_description' => ['nullable', 'string'],
            'admin_description' => ['nullable', 'string'],
            'trip_date' => ['required', 'date'],
            'invoice_number' => ['nullable', 'string', 'max:255'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'stops' => ['nullable', 'array'],
            'stops.*.destination' => ['required_with:stops', 'string', 'max:255'],
            'stops.*.stop_order' => ['required_with:stops', 'integer', 'min:1'],
            'stops.*.notes' => ['nullable', 'string'],
        ];
    }
}
