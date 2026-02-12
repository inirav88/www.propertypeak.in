<?php

namespace Botble\Developer\Services;

use Botble\AgentBroker\Models\AgentProfile;
use Botble\Developer\Models\DeveloperProfile;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Facades\Cache;

class SlugResolverService
{
    public function resolve(string $slug): ?array
    {
        $normalized = app(SlugConflictService::class)->normalize($slug);

        if ($normalized === '' || app(SlugConflictService::class)->isReserved($normalized)) {
            return null;
        }

        return Cache::remember(sprintf('root_slug:%s', $normalized), now()->addMinute(), function () use ($normalized): ?array {
            $slugRecord = Slug::query()->where('key', $normalized)->first();

            if (! $slugRecord) {
                return null;
            }

            return match ($slugRecord->reference_type) {
                DeveloperProfile::class => [
                    'type' => DeveloperProfile::class,
                    'model' => DeveloperProfile::query()->with('projects')->find($slugRecord->reference_id),
                ],
                AgentProfile::class => [
                    'type' => AgentProfile::class,
                    'model' => AgentProfile::query()->find($slugRecord->reference_id),
                ],
                default => null,
            };
        });
    }
}
