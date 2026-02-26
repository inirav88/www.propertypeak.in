<?php

namespace App\Modules\RealEstate\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\RealEstate\Models\DeveloperProject;
use App\Modules\RealEstate\Models\Property;
use App\Modules\RealEstate\Services\DeveloperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DeveloperDashboardController extends Controller
{
    public function __construct(
        private DeveloperService $developerService,
    ) {}

    /**
     * Display developer dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        $developer = $user->developer;
        
        if (!$developer) {
            return view('realestate::dashboard.developer.setup');
        }

        $stats = $this->developerService->getDashboardStats($developer->id);
        $recentProjects = $developer->projects()->latest()->take(5)->get();
        $recentProperties = $developer->properties()->latest()->take(5)->get();

        return view('realestate::dashboard.developer.index', compact(
            'developer',
            'stats',
            'recentProjects',
            'recentProperties'
        ));
    }

    /**
     * Show profile edit form.
     */
    public function profile(): View
    {
        $user = Auth::user();
        $developer = $user->developer;

        return view('realestate::dashboard.developer.profile', compact('developer'));
    }

    /**
     * Update developer profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tagline' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'website' => 'nullable|url|max:255',
            'office_address' => 'nullable|string|max:500',
            'registration_number' => 'nullable|string|max:100',
            'rera_id' => 'nullable|string|max:100',
            'established_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'employee_count' => 'nullable|integer|min:1',
            'logo' => 'nullable|image|max:2048',
            'banner_image' => 'nullable|image|max:5120',
            'social_links' => 'nullable|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $this->developerService->saveProfile($user, $validated);

        return redirect()->route('developer-portal.profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * List all projects.
     */
    public function projects(): View
    {
        $user = Auth::user();
        $developer = $user->developer;
        
        $projects = $this->developerService->getProjects($developer->id, 10);

        return view('realestate::dashboard.developer.projects.index', compact('projects'));
    }

    /**
     * Show create project form.
     */
    public function createProject(): View
    {
        return view('realestate::dashboard.developer.projects.create');
    }

    /**
     * Store new project.
     */
    public function storeProject(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $developer = $user->developer;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'type' => 'required|in:residential,commercial,mixed_use,industrial,retail',
            'status' => 'required|in:upcoming,ongoing,completed',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'rera_number' => 'nullable|string|max:100',
            'launch_date' => 'nullable|date',
            'possession_date' => 'nullable|date',
            'total_units' => 'nullable|integer|min:1',
            'price_starting_from' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|array',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:2048',
            'brochure' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $project = $this->developerService->createProject($developer->id, $validated);

        return redirect()->route('developer-portal.projects.index')
            ->with('success', 'Project created successfully and pending approval.');
    }

    /**
     * Show edit project form.
     */
    public function editProject(string $slug): View
    {
        $user = Auth::user();
        $developer = $user->developer;
        
        $project = DeveloperProject::where('slug', $slug)
            ->where('developer_id', $developer->id)
            ->firstOrFail();

        $this->authorize('update', $project);

        return view('realestate::dashboard.developer.projects.edit', compact('project'));
    }

    /**
     * Update project.
     */
    public function updateProject(Request $request, string $slug): RedirectResponse
    {
        $user = Auth::user();
        $developer = $user->developer;
        
        $project = DeveloperProject::where('slug', $slug)
            ->where('developer_id', $developer->id)
            ->firstOrFail();

        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'type' => 'required|in:residential,commercial,mixed_use,industrial,retail',
            'status' => 'required|in:upcoming,ongoing,completed',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'rera_number' => 'nullable|string|max:100',
            'launch_date' => 'nullable|date',
            'possession_date' => 'nullable|date',
            'total_units' => 'nullable|integer|min:1',
            'price_starting_from' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|array',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:2048',
            'brochure' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $this->developerService->updateProject($project, $validated);

        return redirect()->route('developer-portal.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Delete project.
     */
    public function destroyProject(string $slug): RedirectResponse
    {
        $user = Auth::user();
        $developer = $user->developer;
        
        $project = DeveloperProject::where('slug', $slug)
            ->where('developer_id', $developer->id)
            ->firstOrFail();

        $this->authorize('delete', $project);

        $this->developerService->deleteProject($project);

        return redirect()->route('developer-portal.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    /**
     * List all properties.
     */
    public function properties(): View
    {
        $user = Auth::user();
        
        $properties = $this->developerService->getProperties($user->id, 10);

        return view('realestate::dashboard.developer.properties.index', compact('properties'));
    }

    /**
     * Show create property form.
     */
    public function createProperty(): View
    {
        $user = Auth::user();
        $developer = $user->developer;
        
        $projects = $developer->projects()->pluck('name', 'id');

        return view('realestate::dashboard.developer.properties.create', compact('projects'));
    }

    /**
     * Store new property.
     */
    public function storeProperty(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:sale,rent,pg',
            'property_type' => 'required|in:apartment,house,villa,plot,commercial,office',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'carpet_area' => 'nullable|numeric|min:0',
            'developer_project_id' => 'nullable|exists:developer_projects,id',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        $property = $this->developerService->createProperty($user->id, $validated);

        return redirect()->route('developer-portal.properties.index')
            ->with('success', 'Property created successfully and pending approval.');
    }

    /**
     * Show edit property form.
     */
    public function editProperty(string $slug): View
    {
        $user = Auth::user();
        $developer = $user->developer;
        
        $property = Property::withoutGlobalScope('approved')
            ->where('slug', $slug)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $this->authorize('update', $property);

        $projects = $developer->projects()->pluck('name', 'id');

        return view('realestate::dashboard.developer.properties.edit', compact('property', 'projects'));
    }

    /**
     * Update property.
     */
    public function updateProperty(Request $request, string $slug): RedirectResponse
    {
        $user = Auth::user();
        
        $property = Property::withoutGlobalScope('approved')
            ->where('slug', $slug)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $this->authorize('update', $property);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:sale,rent,pg',
            'property_type' => 'required|in:apartment,house,villa,plot,commercial,office',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'carpet_area' => 'nullable|numeric|min:0',
            'developer_project_id' => 'nullable|exists:developer_projects,id',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        $this->developerService->updateProperty($property, $validated);

        return redirect()->route('developer-portal.properties.index')
            ->with('success', 'Property updated successfully.');
    }

    /**
     * Delete property.
     */
    public function destroyProperty(string $slug): RedirectResponse
    {
        $user = Auth::user();
        
        $property = Property::withoutGlobalScope('approved')
            ->where('slug', $slug)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $this->authorize('delete', $property);

        $this->developerService->deleteProperty($property);

        return redirect()->route('developer-portal.properties.index')
            ->with('success', 'Property deleted successfully.');
    }

    /**
     * Show stats page.
     */
    public function stats(): View
    {
        $user = Auth::user();
        $developer = $user->developer;
        
        $stats = $this->developerService->getDashboardStats($developer->id);

        return view('realestate::dashboard.developer.stats', compact('stats'));
    }
}
