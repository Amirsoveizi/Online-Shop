<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $address = $this->route('address');

        return $this->user()->isAdmin() || $this->user()->id === $address->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'label' => 'sometimes|required|string|max:255',
            'apartment' => 'sometimes|nullable|string|max:255',
            'street' => 'sometimes|required|string|max:255',
            'area' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'state' => 'sometimes|required|string|max:255',
            'postal_code' => 'sometimes|required|string|max:20',
            'country' => 'sometimes|required|string|max:255',
        ];
    }
}
