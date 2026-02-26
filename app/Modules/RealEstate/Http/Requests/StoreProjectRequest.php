<?php

namespace App\Modules\RealEstate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->isDeveloper() && $user->isApproved();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:100',
            'short_description' => 'nullable|string|max:500',
            'type' => 'required|in:residential,commercial,mixed_use,industrial,retail',
            'status' => 'required|in:upcoming,ongoing,completed,sold_out',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'rera_number' => 'nullable|string|max:100',
            'legal_clearance' => 'nullable|string|max:255',
            'launch_date' => 'nullable|date|before_or_equal:possession_date',
            'possession_date' => 'nullable|date|after_or_equal:launch_date',
            'completion_date' => 'nullable|date',
            'unit_configurations' => 'nullable|array',
            'unit_configurations.*' => 'string|max:100',
            'total_units' => 'nullable|integer|min:1',
            'total_towers' => 'nullable|integer|min:1',
            'total_floors' => 'nullable|integer|min:1',
            'total_area' => 'nullable|numeric|min:0',
            'price_starting_from' => 'nullable|numeric|min:0',
            'price_per_sqft' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100',
            'specifications' => 'nullable|array',
            'nearby_facilities' => 'nullable|array',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'floor_plans' => 'nullable|array',
            'brochure' => 'nullable|file|mimes:pdf|max:10240',
            'video_url' => 'nullable|url|max:500',
            'virtual_tour_url' => 'nullable|url|max:500',
            'highlights' => 'nullable|array',
            'highlights.*' => 'string|max:200',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
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
            'name' => 'project name',
            'rera_number' => 'RERA number',
            'launch_date' => 'launch date',
            'possession_date' => 'possession date',
            'price_starting_from' => 'starting price',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the project name.',
            'description.required' => 'Please provide a project description.',
            'description.min' => 'The description must be at least 100 characters.',
            'launch_date.before_or_equal' => 'Launch date must be before or equal to possession date.',
            'possession_date.after_or_equal' => 'Possession date must be after or equal to launch date.',
            'brochure.mimes' => 'The brochure must be a PDF file.',
            'brochure.max' => 'The brochure must not exceed 10MB.',
        ];
    }
}
