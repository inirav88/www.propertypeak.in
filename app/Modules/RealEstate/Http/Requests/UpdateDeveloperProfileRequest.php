<?php

namespace App\Modules\RealEstate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeveloperProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->isDeveloper();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tagline' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'website' => 'nullable|url|max:255',
            'office_address' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'social_links' => 'nullable|array',
            'social_links.facebook' => 'nullable|url|max:255',
            'social_links.twitter' => 'nullable|url|max:255',
            'social_links.linkedin' => 'nullable|url|max:255',
            'social_links.instagram' => 'nullable|url|max:255',
            'registration_number' => 'nullable|string|max:100',
            'rera_id' => 'nullable|string|max:100',
            'established_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'total_projects' => 'nullable|integer|min:0',
            'completed_projects' => 'nullable|integer|min:0',
            'ongoing_projects' => 'nullable|integer|min:0',
            'employee_count' => 'nullable|integer|min:1',
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
            'company_name' => 'company name',
            'rera_id' => 'RERA ID',
            'established_year' => 'established year',
            'social_links' => 'social media links',
            'banner_image' => 'banner image',
        ];
    }
}
