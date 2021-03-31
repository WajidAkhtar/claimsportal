<?php

namespace App\Domains\Auth\Http\Requests\Backend\User;

use App\Domains\Auth\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateUserRequest.
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return ! ($this->user->isMasterAdmin() && ! $this->user()->isMasterAdmin());
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
            'type' => [Rule::requiredIf(function () {
                return ! $this->user->isMasterAdmin();
            }), Rule::in([User::TYPE_ADMIN, User::TYPE_USER])],
            'first_name' => ['required', 'max:100'],
            'last_name' => ['required', 'max:100'],
            'job_title' => ['max:100'],
            'department' => ['max:100'],
            'organisation_id' => ['required'],
            'email' => ['required', 'max:255', 'email', Rule::unique('users')->ignore($this->user->id)],
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

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new AuthorizationException(__('Only the administrator can update this user.'));
    }
}
