<?php

namespace Botble\RealEstate\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Models\Project;
use Botble\RealEstate\Models\Property;

class BuilderMicrositeController extends BaseController
{
    public function show($slug)
    {
        $builder = Account::where('microsite_slug', $slug)
            ->where('microsite_enabled', true)
            ->where(function ($q) {
                $q->where('type', 'builder')
                    ->orWhere('type', 'developer');
            })
            ->firstOrFail();

        // Check if package allows microsite
        if (!$builder->hasPackageFeature('microsite')) {
            abort(404, 'Microsite not available');
        }

        $completedProjects = Project::where('author_id', $builder->id)
            ->where('status', 'sold')
            ->latest()
            ->paginate(6, ['*'], 'completed_page');

        $ongoingProjects = Project::where('author_id', $builder->id)
            ->whereIn('status', ['pre_sale', 'selling', 'building'])
            ->latest()
            ->paginate(6, ['*'], 'ongoing_page');

        $properties = Property::where('author_id', $builder->id)
            ->where('moderation_status', 'approved')
            ->latest()
            ->paginate(12);

        return view('plugins/real-estate::themes.microsite.index', compact('builder', 'completedProjects', 'ongoingProjects', 'properties'));
    }
}
