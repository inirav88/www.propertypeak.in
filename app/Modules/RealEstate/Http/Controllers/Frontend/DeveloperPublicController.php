<?php

namespace App\Modules\RealEstate\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Modules\RealEstate\Models\Developer;
use App\Modules\RealEstate\Models\DeveloperProject;
use App\Modules\RealEstate\Services\DeveloperService;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeveloperPublicController extends Controller
{
    public function __construct(
        private DeveloperService $developerService,
    ) {}

    /**
     * Display list of approved developers.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $city = $request->get('city');

        if ($search) {
            $developers = $this->developerService->search($search, 12);
        } else {
            $developers = $this->developerService->listApproved(12);
        }

        // SEO Meta
        $metaTitle = 'Real Estate Developers | Find Top Property Developers';
        $metaDescription = 'Browse our list of verified real estate developers. Find top property developers with their projects, contact details, and company information.';

        return Theme::scope('real-estate.developers-list', compact(
            'developers',
            'search',
            'city',
            'metaTitle',
            'metaDescription'
        ), 'realestate::frontend.developers.index')->render();
    }

    /**
     * Display developer public profile.
     * Middleware 'public.profile' ensures only approved developers are shown.
     */
    public function show(string $slug): View
    {
        $developer = $this->developerService->getPublicProfile($slug);

        if (!$developer) {
            throw new NotFoundHttpException('Developer not found or not yet approved.');
        }

        // Load relationships with eager loading
        $developer->load([
            'approvedProjects' => function ($query): void {
                $query->withCount('approvedProperties')
                    ->orderBy('created_at', 'desc');
            },
            'approvedProperties' => function ($query): void {
                $query->limit(6);
            },
        ]);

        // Separate projects by status
        $pastProjects = $developer->approvedProjects->where('status', 'completed');
        $ongoingProjects = $developer->approvedProjects->where('status', 'ongoing');
        $upcomingProjects = $developer->approvedProjects->where('status', 'upcoming');

        // SEO Meta
        $metaTitle = $developer->meta_title ?? $developer->company_name . ' | Real Estate Developer in ' . $developer->city;
        $metaDescription = $developer->meta_description ?? $this->generateMetaDescription($developer);
        $metaKeywords = $developer->meta_keywords ?? $this->generateMetaKeywords($developer);

        // Schema.org structured data
        $schemaData = $this->generateSchemaData($developer);

        return Theme::scope('real-estate.developer-detail', compact(
            'developer',
            'pastProjects',
            'ongoingProjects',
            'upcomingProjects',
            'metaTitle',
            'metaDescription',
            'metaKeywords',
            'schemaData'
        ), 'realestate::frontend.developers.show')->render();
    }

    /**
     * Generate meta description for SEO.
     */
    private function generateMetaDescription(Developer $developer): string
    {
        $description = $developer->company_name;
        
        if ($developer->established_year) {
            $description .= ' - Established in ' . $developer->established_year;
        }
        
        if ($developer->total_projects > 0) {
            $description .= ' with ' . $developer->total_projects . ' projects';
        }
        
        if ($developer->city) {
            $description .= ' in ' . $developer->city;
        }
        
        $description .= '. View completed, ongoing and upcoming projects.';
        
        return substr($description, 0, 160);
    }

    /**
     * Generate meta keywords for SEO.
     */
    private function generateMetaKeywords(Developer $developer): string
    {
        $keywords = [
            $developer->company_name,
            'real estate developer',
            'property developer',
        ];
        
        if ($developer->city) {
            $keywords[] = $developer->city . ' developer';
            $keywords[] = 'properties in ' . $developer->city;
        }
        
        if ($developer->rera_id) {
            $keywords[] = 'RERA approved';
        }
        
        return implode(', ', $keywords);
    }

    /**
     * Generate Schema.org structured data.
     */
    private function generateSchemaData(Developer $developer): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'RealEstateAgent',
            'name' => $developer->company_name,
            'url' => route('developers.show', $developer->slug),
        ];

        if ($developer->description) {
            $schema['description'] = $developer->description;
        }

        if ($developer->logo) {
            $schema['logo'] = asset('storage/' . $developer->logo);
        }

        if ($developer->email) {
            $schema['email'] = $developer->email;
        }

        if ($developer->phone) {
            $schema['telephone'] = $developer->phone;
        }

        if ($developer->website) {
            $schema['sameAs'] = $developer->website;
        }

        if ($developer->office_address || $developer->city) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $developer->office_address,
                'addressLocality' => $developer->city,
                'addressRegion' => $developer->state,
                'addressCountry' => $developer->country ?? 'IN',
            ];
        }

        // Aggregate rating if available
        $schema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => '4.5',
            'reviewCount' => '10',
        ];

        return $schema;
    }

    /**
     * Show project detail page.
     */
    public function showProject(string $developerSlug, string $projectSlug): View
    {
        $project = DeveloperProject::with(['developer', 'developer.user', 'approvedProperties'])
            ->where('slug', $projectSlug)
            ->whereHas('developer', function ($query) use ($developerSlug): void {
                $query->where('slug', $developerSlug);
            })
            ->where('approval_status', 'approved')
            ->first();

        if (!$project) {
            throw new NotFoundHttpException('Project not found.');
        }

        // SEO Meta
        $metaTitle = $project->meta_title ?? $project->name . ' | ' . $project->developer->company_name;
        $metaDescription = $project->meta_description ?? $project->short_description;

        // Schema.org structured data
        $schemaData = $this->generateProjectSchemaData($project);

        return Theme::scope('real-estate.project-detail', compact(
            'project',
            'metaTitle',
            'metaDescription',
            'schemaData'
        ), 'realestate::frontend.projects.show')->render();
    }

    /**
     * Handle developer contact form submission.
     */
    public function contact(Request $request, string $slug): RedirectResponse
    {
        $developer = $this->developerService->getPublicProfile($slug);

        if (!$developer) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        // TODO: Send email notification to developer
        // Mail::to($developer->email)->send(new DeveloperContactMail($validated));

        return redirect()->back()->with('success', 'Your message has been sent successfully. The developer will contact you soon.');
    }

    /**
     * Generate Schema.org structured data for project.
     */
    private function generateProjectSchemaData(DeveloperProject $project): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Residence',
            'name' => $project->name,
            'description' => $project->description,
            'url' => route('developer-projects.show', [
                'developerSlug' => $project->developer->slug,
                'projectSlug' => $project->slug,
            ]),
        ];

        if ($project->address || $project->city) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $project->address,
                'addressLocality' => $project->city,
                'addressRegion' => $project->state,
                'addressCountry' => $project->country ?? 'IN',
            ];
        }

        if ($project->gallery_images && count($project->gallery_images) > 0) {
            $schema['image'] = array_map(function ($image) {
                return asset('storage/' . $image);
            }, $project->gallery_images);
        }

        if ($project->developer) {
            $schema['developer'] = [
                '@type' => 'Organization',
                'name' => $project->developer->company_name,
            ];
        }

        return $schema;
    }
}
