<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminAddRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6|same:confirm',
            'phone' => 'required|regex:/^01[0125][0-9]{8}$/|unique:admins,phone',
            'role' => 'required|in:super admin,admin,manager,sales,support',
            'gender' => 'required|in:male,female',
            'location' => 'required',
            'age' => 'required|integer|between:18,60',
            'img' => 'required|image',
        ];
    }
}
