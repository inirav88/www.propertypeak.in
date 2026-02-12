<?php

namespace Botble\Developer\Http\Requests;

use Botble\Developer\Models\DeveloperProfile;
use Botble\Developer\Models\DeveloperProject;
use Botble\Support\Http\Requests\Request;

class DeveloperPublicLeadRequest extends Request
{
    public function rules(): array
    {
        $slug = (string) $this->route('slug');

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'message' => ['required', 'string', 'max:1000'],
            'project_id' => [
                'nullable',
                'integer',
                function (string $attribute, mixed $value, \Closure $fail) use ($slug): void {
                    if (! $value) {
                        return;
                    }

                    $developer = DeveloperProfile::query()->where('slug', $slug)->first();

                    if (! $developer) {
                        $fail(__('The selected developer is invalid.'));

                        return;
                    }

                    $exists = DeveloperProject::query()
                        ->where('id', (int) $value)
                        ->where('developer_profile_id', $developer->id)
                        ->exists();

                    if (! $exists) {
                        $fail(__('The selected project does not belong to this developer.'));
                    }
                },
            ],
            'website' => [
                'nullable',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (filled($value)) {
                        $fail(__('Spam protection triggered.'));
                    }
                },
            ],
        ];
    }
}
