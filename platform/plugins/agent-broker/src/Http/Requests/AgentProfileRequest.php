<?php

namespace Botble\AgentBroker\Http\Requests;

use Botble\AgentBroker\Models\AgentProfile;
use Botble\Developer\Services\SlugConflictService;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AgentProfileRequest extends Request
{
    public function rules(): array
    {
        $id = $this->route('agent')?->id;

        return [
            'account_id' => ['required', 'integer', Rule::exists('re_accounts', 'id'), Rule::unique('re_agent_profiles', 'account_id')->ignore($id)],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique((new AgentProfile())->getTable(), 'slug')->ignore($id),
                function (string $attribute, mixed $value, \Closure $fail) use ($id): void {
                    $service = app(SlugConflictService::class);

                    if (! $service->isAvailable((string) $value, AgentProfile::class, $id)) {
                        $fail(__('The :attribute has already been taken or reserved.', ['attribute' => $attribute]));
                    }
                },
            ],
            'status' => ['required', Rule::in(['active', 'inactive', 'draft'])],
            'requires_approval' => ['nullable', 'boolean'],
            'auto_approve_changes' => ['nullable', 'boolean'],
            'is_verified' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_blocked' => ['nullable', 'boolean'],
        ];
    }
}
