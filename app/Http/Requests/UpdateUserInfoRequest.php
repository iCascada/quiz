<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserInfoRequest extends FormRequest
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
            'email' => 'required|email',
            'name' => 'required|min:2|cyrillic',
            'last_name' => 'required|min:2|cyrillic',
            'department_id' => 'required',
            'role_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('custom_validation.email.required'),
            'email.email' => __('custom_validation.email.email'),
            'name.required' => __('custom_validation.name.required'),
            'name.min' => __('custom_validation.name.min'),
            'name.cyrillic' => __('custom_validation.name.cyrillic'),
            'last_name.cyrillic' => __('custom_validation.last_name.cyrillic'),
            'last_name.required' => __('custom_validation.last_name.required'),
            'last_name.min' => __('custom_validation.last_name.min'),
        ];
    }
}
