<?php

namespace Botble\Developer\Http\Requests;

use Botble\Developer\Models\DeveloperProfile;
use Botble\Developer\Services\SlugConflictService;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class DeveloperProfileRequest extends Request
{
    public function rules(): array
    {
        $id = $this->route('developer')?->id;

        return [
            'account_id' => ['required', 'integer', Rule::exists('re_accounts', 'id'), Rule::unique('re_developer_profiles', 'account_id')->ignore($id)],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique((new DeveloperProfile())->getTable(), 'slug')->ignore($id),
                function (string $attribute, mixed $value, \Closure $fail) use ($id): void {
                    $service = app(SlugConflictService::class);

                    if (! $service->isAvailable((string) $value, DeveloperProfile::class, $id)) {
                        $fail(__('The :attribute has already been taken or reserved.', ['attribute' => $attribute]));
                    }
                },
            ],
            'company_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', Rule::in(['active', 'inactive', 'draft'])],
            'requires_approval' => ['nullable', 'boolean'],
            'auto_approve_changes' => ['nullable', 'boolean'],
            'is_verified' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_blocked' => ['nullable', 'boolean'],
        ];
    }
}
