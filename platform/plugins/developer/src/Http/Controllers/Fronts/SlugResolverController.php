<?php

namespace Botble\Developer\Http\Controllers\Fronts;

use Botble\AgentBroker\Models\AgentProfile;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Developer\Models\DeveloperProfile;
use Botble\Developer\Services\SlugResolverService;
use Botble\Theme\Facades\Theme;

class SlugResolverController extends BaseController
{
    public function resolve(SlugResolverService $service)
    {
        $slug = (string) request()->segment(1);

        if ($slug === '' || str_contains($slug, '/')) {
            abort(404);
        }

        $resolved = $service->resolve($slug);

        if (! $resolved || ! $resolved['model']) {
            abort(404);
        }

        return match ($resolved['type']) {
            DeveloperProfile::class => Theme::scope('developer.public-profile', ['profile' => $resolved['model']], 'plugins/developer::fronts.public-developer')->render(),
            AgentProfile::class => Theme::scope('agent-broker.public-profile', ['profile' => $resolved['model']], 'plugins/agent-broker::fronts.public-agent')->render(),
            default => abort(404),
        };
    }
}
