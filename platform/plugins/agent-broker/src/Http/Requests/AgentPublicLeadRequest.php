<?php

namespace Botble\AgentBroker\Http\Requests;

use Botble\AgentBroker\Models\AgentProfile;
use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Models\Property;
use Botble\Support\Http\Requests\Request;

class AgentPublicLeadRequest extends Request
{
    public function rules(): array
    {
        $slug = (string) $this->route('slug');

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'message' => ['required', 'string', 'max:1000'],
            'property_id' => [
                'nullable',
                'integer',
                function (string $attribute, mixed $value, \Closure $fail) use ($slug): void {
                    if (! $value) {
                        return;
                    }

                    $agent = AgentProfile::query()->where('slug', $slug)->first();

                    if (! $agent) {
                        $fail(__('The selected agent is invalid.'));

                        return;
                    }

                    $exists = Property::query()
                        ->where('id', (int) $value)
                        ->where('author_id', $agent->account_id)
                        ->where('author_type', Account::class)
                        ->exists();

                    if (! $exists) {
                        $fail(__('The selected property does not belong to this agent.'));
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
