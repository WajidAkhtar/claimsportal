<?php

namespace App\Domains\Claim\Http\Requests\Backend\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Class StoreProjectRequest.
 */
class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the project is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return $this->user()->isMasterAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:191',
            'number' => 'required|max:191',
            'pool_id' => 'required',
            'start_date' => 'required|date_format:m-Y',
            'length' => 'required|numeric',
            'status' => 'required|max:191',
            'funders' => 'required|array',
            'funders.*' => 'required|numeric|exists:organisations,id',
            'project_partners.*' => 'required|numeric|exists:organisations,id',
            'cost_items' => 'required|array',
            'cost_items.*.name' => 'required|max:191',
            'cost_items.*.description' => 'required|max:191',
            'cost_items_order' => '',
            'project_funder_ref' => 'required|max:191',
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
        // throw new AuthorizationException(__('Only the administrator can create project.'));
    }
}
