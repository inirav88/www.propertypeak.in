<?php

namespace Botble\Developer\Services;

use Botble\Developer\Models\DeveloperProject;
use Botble\Shortcode\Compilers\Shortcode;

class DeveloperProjectShortcodeService
{
    public function renderProjects(Shortcode $shortcode): string
    {
        $limit = max(1, min((int) ($shortcode->limit ?: 12), 50));
        $order = strtolower((string) ($shortcode->order ?: 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSorts = ['id', 'name', 'created_at', 'updated_at', 'published_at', 'possession_date'];
        $sortBy = in_array((string) $shortcode->sort_by, $allowedSorts, true) ? (string) $shortcode->sort_by : 'created_at';

        $projectStatus = trim((string) ($shortcode->status ?: ''));
        $developerSlug = trim((string) ($shortcode->developer_slug ?: ''));
        $featured = filter_var($shortcode->featured, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        $projects = DeveloperProject::query()
            ->with(['developerProfile'])
            ->whereNotNull('approved_at')
            ->where('approval_status', 'approved')
            ->where('status', 'published')
            ->when($projectStatus !== '', fn ($query) => $query->where('project_status', $projectStatus))
            ->when($featured !== null, fn ($query) => $query->where('is_featured', $featured))
            ->whereHas('developerProfile', function ($query) use ($developerSlug): void {
                $query
                    ->whereNotNull('approved_at')
                    ->where('status', 'active')
                    ->where('is_blocked', false)
                    ->when($developerSlug !== '', fn ($q) => $q->where('slug', app(SlugConflictService::class)->normalize($developerSlug)));
            })
            ->orderBy($sortBy, $order)
            ->simplePaginate($limit);

        return view('plugins/developer::shortcodes.projects', [
            'projects' => $projects,
            'shortcode' => $shortcode,
        ])->render();
    }
}
