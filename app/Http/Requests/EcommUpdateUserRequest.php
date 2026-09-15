<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EcommUpdateUserRequest extends FormRequest
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
        $user_id = Auth::guard('ecomm')->user()->id;

        return [
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'email' => [
                'required',
                'email',
                Rule::unique('ecomm_users', 'email')->ignore($user_id),
            ],

            'phone' => [
                'required',
                'regex:/^01[0125][0-9]{8}$/',
                Rule::unique('ecomm_users', 'phone')->ignore($user_id),
            ],
            'image' => 'image',
        ];
    }
}
