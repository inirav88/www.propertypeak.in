<?php

namespace Botble\Developer\Http\Requests;

use Botble\Developer\Models\DeveloperProject;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class DeveloperProjectRequest extends Request
{
    public function rules(): array
    {
        $id = $this->route('project')?->id;

        return [
            'developer_profile_id' => ['required', 'integer', Rule::exists('re_developer_profiles', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique((new DeveloperProject())->getTable(), 'slug')->ignore($id)],
            'project_status' => ['required', Rule::in(['ongoing', 'completed', 'upcoming'])],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'approval_status' => ['nullable', Rule::in(['pending', 'approved', 'rejected'])],
        ];
    }
}
