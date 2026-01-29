<?php

namespace Botble\RealEstate\Http\Requests;

class AccountProjectRequest extends ProjectRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        return [
            ...$rules,
            'content' => ['nullable', 'string', 'max:100000'],
        ];
    }
}
