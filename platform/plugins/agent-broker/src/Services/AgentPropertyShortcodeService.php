<?php

namespace Botble\AgentBroker\Services;

use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Models\Property;
use Botble\Shortcode\Compilers\Shortcode;

class AgentPropertyShortcodeService
{
    public function renderProperties(Shortcode $shortcode): string
    {
        $limit = max(1, min((int) ($shortcode->limit ?: 12), 50));
        $order = strtolower((string) ($shortcode->order ?: 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSorts = ['id', 'name', 'created_at', 'updated_at', 'price'];
        $sortBy = in_array((string) $shortcode->sort_by, $allowedSorts, true) ? (string) $shortcode->sort_by : 'created_at';

        $status = trim((string) ($shortcode->status ?: ''));
        $agentSlug = trim((string) ($shortcode->agent_slug ?: ''));
        $featured = filter_var($shortcode->featured, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        $properties = Property::query()
            ->with(['author', 'slugable'])
            ->select('re_properties.*')
            ->join('re_agent_profiles', function ($join): void {
                $join
                    ->on('re_agent_profiles.account_id', '=', 're_properties.author_id')
                    ->where('re_properties.author_type', '=', Account::class);
            })
            ->where('re_properties.moderation_status', ModerationStatusEnum::APPROVED)
            ->whereNotNull('re_agent_profiles.approved_at')
            ->where('re_agent_profiles.status', 'active')
            ->where('re_agent_profiles.is_blocked', false)
            ->when($status !== '', fn ($query) => $query->where('re_properties.status', $status))
            ->when($featured !== null, fn ($query) => $query->where('re_properties.is_featured', $featured))
            ->when($agentSlug !== '', fn ($query) => $query->where('re_agent_profiles.slug', app(\Botble\Developer\Services\SlugConflictService::class)->normalize($agentSlug)))
            ->orderBy('re_properties.' . $sortBy, $order)
            ->simplePaginate($limit);

        return view('plugins/agent-broker::shortcodes.properties', [
            'properties' => $properties,
            'shortcode' => $shortcode,
        ])->render();
    }
}
