<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Role;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userToUpdate = $this->route('user');

        return $this->user()->isAdmin() || $this->user()->id === $userToUpdate->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userToUpdate = $this->route('user');

        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userToUpdate->id),
            ],
            'password' => 'sometimes|nullable|string|min:8|confirmed',
            'role' => ['sometimes','required', Rule::in(Role::values())],
        ];
    }
}
