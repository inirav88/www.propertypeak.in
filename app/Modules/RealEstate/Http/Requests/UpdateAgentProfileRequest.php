<?php

namespace App\Modules\RealEstate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAgentProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->isAgent();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'bio' => 'nullable|string',
            'tagline' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:100',
            'rera_id' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'website' => 'nullable|url|max:255',
            'office_address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'social_links' => 'nullable|array',
            'social_links.facebook' => 'nullable|url|max:255',
            'social_links.twitter' => 'nullable|url|max:255',
            'social_links.linkedin' => 'nullable|url|max:255',
            'social_links.instagram' => 'nullable|url|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'specializations' => 'nullable|array',
            'specializations.*' => 'string|max:100',
            'languages_spoken' => 'nullable|array',
            'languages_spoken.*' => 'string|max:50',
            'service_areas' => 'nullable|array',
            'service_areas.*' => 'string|max:100',
            'working_hours' => 'nullable|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'rera_id' => 'RERA ID',
            'experience_years' => 'years of experience',
            'languages_spoken' => 'languages spoken',
            'service_areas' => 'service areas',
            'working_hours' => 'working hours',
        ];
    }
}
