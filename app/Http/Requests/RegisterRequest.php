<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends LoginRequest
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
        $additionalRules = [
            'name' => 'required|min:2|cyrillic',
            'last_name' => 'required|min:2|cyrillic',
            'confirm_password' => 'required|same:password',
            'department_id' => 'required',
        ];

        return array_merge($additionalRules, parent::rules());
    }

    public function messages(): array
    {
        $additionalMessages = [
            'name.required' => __('custom_validation.name.required'),
            'name.min' => __('custom_validation.name.min'),
            'name.cyrillic' => __('custom_validation.name.cyrillic'),
            'last_name.cyrillic' => __('custom_validation.last_name.cyrillic'),
            'last_name.required' => __('custom_validation.last_name.required'),
            'last_name.min' => __('custom_validation.last_name.min'),
            'confirm_password.required' => __('custom_validation.confirm_password.required'),
            'confirm_password.same' => __('custom_validation.confirm_password.same'),
            'department_id.required' => __('custom_validation.department_id.required')
        ];

        return array_merge($additionalMessages, parent::messages());
    }
}
