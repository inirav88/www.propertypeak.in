<?php

namespace App\Modules\RealEstate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && ($user->isDeveloper() || $user->isAgent()) && $user->isApproved();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'type' => 'required|in:sale,rent,pg',
            'property_type' => 'required|in:apartment,house,villa,plot,commercial,office',
            'price' => 'required|numeric|min:0',
            'price_per_sqft' => 'nullable|numeric|min:0',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'bedrooms' => 'nullable|integer|min:0|max:50',
            'bathrooms' => 'nullable|integer|min:0|max:50',
            'balconies' => 'nullable|integer|min:0|max:50',
            'carpet_area' => 'nullable|numeric|min:0',
            'built_up_area' => 'nullable|numeric|min:0',
            'super_built_up_area' => 'nullable|numeric|min:0',
            'total_floors' => 'nullable|integer|min:1',
            'floor_number' => 'nullable|integer|min:0',
            'parking_spaces' => 'nullable|integer|min:0',
            'furnishing_status' => 'nullable|integer|in:0,1,2',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100',
            'nearby_facilities' => 'nullable|array',
            'nearby_facilities.*' => 'string|max:100',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'video_url' => 'nullable|url|max:500',
            'virtual_tour_url' => 'nullable|url|max:500',
            'rera_number' => 'nullable|string|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'developer_project_id' => 'nullable|exists:developer_projects,id',
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
            'type' => 'property listing type',
            'property_type' => 'property type',
            'carpet_area' => 'carpet area',
            'built_up_area' => 'built-up area',
            'super_built_up_area' => 'super built-up area',
            'developer_project_id' => 'project',
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
            'title.required' => 'Please enter a property title.',
            'description.required' => 'Please provide a property description.',
            'description.min' => 'The description must be at least 50 characters.',
            'price.required' => 'Please enter the property price.',
            'price.numeric' => 'The price must be a valid number.',
            'address.required' => 'Please enter the property address.',
            'city.required' => 'Please enter the city.',
            'state.required' => 'Please enter the state.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.max' => 'Each image must not exceed 2MB.',
        ];
    }
}
