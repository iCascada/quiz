<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'current_password' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => __('custom_validation.current_password.required'),
            'current_password.min' => __('custom_validation.current_password.required'),
            'password.required' => __('custom_validation.password.required'),
            'password.min' => __('custom_validation.password.min'),
            'password.confirmed' => __('custom_validation.password.confirmed'),
            'password_confirmation.required' => __('custom_validation.confirm_password.required'),
        ];
    }
}
