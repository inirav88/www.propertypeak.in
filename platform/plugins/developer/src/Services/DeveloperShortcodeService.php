<?php

namespace Botble\Developer\Services;

use Botble\Developer\Models\DeveloperProfile;
use Botble\Shortcode\Compilers\Shortcode;

class DeveloperShortcodeService
{
    public function renderProfiles(Shortcode $shortcode): string
    {
        $limit = max(1, min((int) ($shortcode->limit ?: 12), 50));
        $order = strtolower((string) ($shortcode->order ?: 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSorts = ['id', 'created_at', 'updated_at', 'company_name'];
        $sortBy = in_array((string) $shortcode->sort_by, $allowedSorts, true) ? (string) $shortcode->sort_by : 'created_at';

        $status = (string) ($shortcode->status ?: 'active');
        $featured = filter_var($shortcode->featured, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $verified = filter_var($shortcode->verified, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $slug = trim((string) ($shortcode->slug ?: ''));

        $profiles = DeveloperProfile::query()
            ->with('slugable')
            ->whereNotNull('approved_at')
            ->where('status', $status)
            ->where('is_blocked', false)
            ->when($featured !== null, fn ($query) => $query->where('is_featured', $featured))
            ->when($verified !== null, fn ($query) => $query->where('is_verified', $verified))
            ->when($slug !== '', fn ($query) => $query->where('slug', app(SlugConflictService::class)->normalize($slug)))
            ->orderBy($sortBy, $order)
            ->simplePaginate($limit);

        return view('plugins/developer::shortcodes.profiles', [
            'profiles' => $profiles,
            'shortcode' => $shortcode,
        ])->render();
    }
}
