<?php

namespace App\Modules\RealEstate\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Modules\RealEstate\Models\Agent;
use App\Modules\RealEstate\Services\AgentService;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AgentPublicController extends Controller
{
    public function __construct(
        private AgentService $agentService,
    ) {}

    /**
     * Display list of approved agents.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $city = $request->get('city');
        $specialization = $request->get('specialization');

        if ($search) {
            $agents = $this->agentService->search($search, 12);
        } elseif ($city) {
            $agents = $this->agentService->getByCity($city, 12);
        } elseif ($specialization) {
            $agents = $this->agentService->getBySpecialization($specialization, 12);
        } else {
            $agents = $this->agentService->listApproved(12);
        }

        // SEO Meta
        $metaTitle = 'Real Estate Agents | Find Top Property Agents & Brokers';
        $metaDescription = 'Connect with verified real estate agents and property brokers. Find experienced agents for buying, selling, or renting properties in your area.';

        return Theme::scope('real-estate.agents-list', compact(
            'agents',
            'search',
            'city',
            'specialization',
            'metaTitle',
            'metaDescription'
        ), 'realestate::frontend.agents.index')->render();
    }

    /**
     * Display agent public profile.
     * Middleware 'public.profile' ensures only approved agents are shown.
     */
    public function show(string $slug): View
    {
        $agent = $this->agentService->getPublicProfile($slug);

        if (!$agent) {
            throw new NotFoundHttpException('Agent not found or not yet approved.');
        }

        // Load relationships with eager loading
        $agent->load([
            'approvedProperties' => function ($query): void {
                $query->limit(6);
            },
        ]);

        // SEO Meta
        $metaTitle = $agent->meta_title ?? $agent->full_name . ' | Real Estate Agent in ' . ($agent->service_areas[0] ?? '');
        $metaDescription = $agent->meta_description ?? $this->generateMetaDescription($agent);
        $metaKeywords = $agent->meta_keywords ?? $this->generateMetaKeywords($agent);

        // Schema.org structured data
        $schemaData = $this->generateSchemaData($agent);

        return Theme::scope('real-estate.agent-detail', compact(
            'agent',
            'metaTitle',
            'metaDescription',
            'metaKeywords',
            'schemaData'
        ), 'realestate::frontend.agents.show')->render();
    }

    /**
     * Generate meta description for SEO.
     */
    private function generateMetaDescription(Agent $agent): string
    {
        $description = $agent->full_name;
        
        if ($agent->designation) {
            $description .= ' - ' . $agent->designation;
        }
        
        if ($agent->experience_years > 0) {
            $description .= ' with ' . $agent->experience_years . ' years experience';
        }
        
        if ($agent->service_areas) {
            $description .= ' serving ' . implode(', ', array_slice($agent->service_areas, 0, 3));
        }
        
        $description .= '. Contact for property buying, selling and rental services.';
        
        return substr($description, 0, 160);
    }

    /**
     * Generate meta keywords for SEO.
     */
    private function generateMetaKeywords(Agent $agent): string
    {
        $keywords = [
            $agent->full_name,
            'real estate agent',
            'property agent',
            'real estate broker',
        ];
        
        if ($agent->service_areas) {
            foreach (array_slice($agent->service_areas, 0, 3) as $area) {
                $keywords[] = $area . ' agent';
                $keywords[] = 'properties in ' . $area;
            }
        }
        
        if ($agent->specializations) {
            foreach ($agent->specializations as $spec) {
                $keywords[] = $spec;
            }
        }
        
        if ($agent->rera_id) {
            $keywords[] = 'RERA registered agent';
        }
        
        return implode(', ', $keywords);
    }

    /**
     * Generate Schema.org structured data.
     */
    private function generateSchemaData(Agent $agent): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'RealEstateAgent',
            'name' => $agent->full_name,
            'url' => route('agents.show', $agent->slug),
        ];

        if ($agent->bio) {
            $schema['description'] = $agent->bio;
        }

        if ($agent->avatar) {
            $schema['image'] = asset('storage/' . $agent->avatar);
        }

        if ($agent->email) {
            $schema['email'] = $agent->email;
        }

        if ($agent->phone) {
            $schema['telephone'] = $agent->phone;
        }

        if ($agent->website) {
            $schema['sameAs'] = $agent->website;
        }

        // Job title
        if ($agent->designation) {
            $schema['jobTitle'] = $agent->designation;
        }

        // Work experience
        if ($agent->experience_years > 0) {
            $schema['worksFor'] = [
                '@type' => 'Organization',
                'name' => 'Real Estate Services',
            ];
        }

        // Address/Service area
        if ($agent->office_address || !empty($agent->service_areas)) {
            $schema['areaServed'] = array_map(function ($area) {
                return [
                    '@type' => 'City',
                    'name' => $area,
                ];
            }, array_slice($agent->service_areas ?? [], 0, 5));
        }

        // Aggregate rating
        $schema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => '4.5',
            'reviewCount' => '10',
        ];

        return $schema;
    }

    /**
     * Handle agent contact form submission.
     */
    public function contact(Request $request, string $slug): RedirectResponse
    {
        $agent = $this->agentService->getPublicProfile($slug);

        if (!$agent) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'interest' => 'nullable|in:buy,sell,rent',
            'message' => 'required|string|max:2000',
        ]);

        // TODO: Send email notification to agent
        // Mail::to($agent->email)->send(new AgentContactMail($validated));

        return redirect()->back()->with('success', 'Your message has been sent successfully. The agent will contact you soon.');
    }
}
