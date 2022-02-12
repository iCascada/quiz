<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|',
            'password' => 'required|min:6',
            'remember' => 'sometimes|required'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('custom_validation.email.required'),
            'email.email' => __('custom_validation.email.email'),
            'password.required' => __('custom_validation.password.required'),
            'password.min' => __('custom_validation.password.min'),
        ];
    }
}
