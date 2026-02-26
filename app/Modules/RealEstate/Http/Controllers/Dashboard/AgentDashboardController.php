<?php

namespace App\Modules\RealEstate\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\RealEstate\Models\Property;
use App\Modules\RealEstate\Services\AgentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AgentDashboardController extends Controller
{
    public function __construct(
        private AgentService $agentService,
    ) {}

    /**
     * Display agent dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        $agent = $user->agent;
        
        if (!$agent) {
            return view('realestate::dashboard.agent.setup');
        }

        $stats = $this->agentService->getDashboardStats($agent->id);
        $recentProperties = $agent->properties()->latest()->take(5)->get();

        return view('realestate::dashboard.agent.index', compact(
            'agent',
            'stats',
            'recentProperties'
        ));
    }

    /**
     * Show profile edit form.
     */
    public function profile(): View
    {
        $user = Auth::user();
        $agent = $user->agent;

        return view('realestate::dashboard.agent.profile', compact('agent'));
    }

    /**
     * Update agent profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        $validated = $request->validate([
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
            'experience_years' => 'nullable|integer|min:0',
            'specializations' => 'nullable|array',
            'languages_spoken' => 'nullable|array',
            'service_areas' => 'nullable|array',
            'working_hours' => 'nullable|array',
            'avatar' => 'nullable|image|max:2048',
            'banner_image' => 'nullable|image|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $this->agentService->saveProfile($user, $validated);

        return redirect()->route('agent-portal.profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * List all properties.
     */
    public function properties(): View
    {
        $user = Auth::user();
        
        $properties = $this->agentService->getProperties($user->id, 10);

        return view('realestate::dashboard.agent.properties.index', compact('properties'));
    }

    /**
     * Show create property form.
     */
    public function createProperty(): View
    {
        return view('realestate::dashboard.agent.properties.create');
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
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        $property = $this->agentService->createProperty($user->id, $validated);

        return redirect()->route('agent-portal.properties.index')
            ->with('success', 'Property created successfully and pending approval.');
    }

    /**
     * Show edit property form.
     */
    public function editProperty(string $slug): View
    {
        $user = Auth::user();
        
        $property = Property::withoutGlobalScope('approved')
            ->where('slug', $slug)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $this->authorize('update', $property);

        return view('realestate::dashboard.agent.properties.edit', compact('property'));
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
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        $this->agentService->updateProperty($property, $validated);

        return redirect()->route('agent-portal.properties.index')
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

        $this->agentService->deleteProperty($property);

        return redirect()->route('agent-portal.properties.index')
            ->with('success', 'Property deleted successfully.');
    }

    /**
     * Show stats page.
     */
    public function stats(): View
    {
        $user = Auth::user();
        $agent = $user->agent;
        
        $stats = $this->agentService->getDashboardStats($agent->id);

        return view('realestate::dashboard.agent.stats', compact('stats'));
    }
}
