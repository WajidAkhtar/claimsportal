<?php

namespace App\Domains\Auth\Http\Requests\Backend\User;

use App\Domains\Auth\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

/**
 * Class StoreUserRequest.
 */
class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(!in_array(current_user_role(), ['Developer', 'Administrator', 'Super User', 'Finance Officer', 'Project Admin', 'Project Partner'])) {
            return false;      
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['required', Rule::in([User::TYPE_ADMIN, User::TYPE_USER])],
            'first_name' => ['required', 'max:100'],
            'last_name' => ['required', 'max:100'],
            'job_title' => ['max:100'],
            'department' => ['max:100'],
            'organisation_id' => ['required'],
            'email' => ['required', 'max:255', 'email', Rule::unique('users')],
            'password' => ['max:100', PasswordRules::register($this->email), 'confirmed'],
            'active' => ['sometimes', 'in:1'],
            'email_verified' => ['sometimes', 'in:1'],
            'send_confirmation_email' => ['sometimes', 'in:1'],
            'roles' => ['sometimes', 'array'],
            'roles.*' => [Rule::exists('roles', 'id')->where('type', $this->type)],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => [Rule::exists('permissions', 'id')->where('type', $this->type)],
            'pools' => ['sometimes', 'array'],
            'building_name_no' => ['max:191'],
            'street' => ['max:191'],
            'address_line_2' => ['max:191'],
            'county' => ['max:191'],
            'city' => ['max:191'],
            'postcode' => ['max:191'],
            'correspending_email' => ['max:191'],
            'mobile' => ['max:191'],
            'direct_dial' => ['max:191'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'roles.*.exists' => __('One or more roles were not found or are not allowed to be associated with this user type.'),
            'permissions.*.exists' => __('One or more permissions were not found or are not allowed to be associated with this user type.'),
        ];
    }
}
