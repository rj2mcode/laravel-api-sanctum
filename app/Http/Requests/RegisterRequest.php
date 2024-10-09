<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3|max:30',
            'phone_number' => 'required|digits:10',
        ];
    }

    /**
     * Get the validation error messages that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'please enter your :attribute',
            'name.min' => 'Name must be least 3 character',
            'name.max' => 'Name must not more than 100 character',
            'email.required' => 'please enter your :attribute',
        ];
    }
}
