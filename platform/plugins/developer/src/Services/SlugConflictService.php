<?php

namespace Botble\Developer\Services;

use Botble\AgentBroker\Models\AgentProfile;
use Botble\Developer\Models\DeveloperProfile;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SlugConflictService
{
    public function normalize(string $slug): string
    {
        return trim(Str::slug($slug));
    }

    public function reservedKeywords(): array
    {
        return array_values(array_unique(array_filter(Arr::wrap(config('reserved-slugs.root', [])))));
    }

    public function isReserved(string $slug): bool
    {
        return in_array($this->normalize($slug), $this->reservedKeywords(), true);
    }

    public function isTaken(string $slug, ?string $exceptType = null, ?int $exceptId = null): bool
    {
        $normalized = $this->normalize($slug);

        $slugQuery = Slug::query()->where('key', $normalized);

        if ($exceptType && $exceptId) {
            $slugQuery->where(function ($query) use ($exceptType, $exceptId): void {
                $query
                    ->where('reference_type', '!=', $exceptType)
                    ->orWhere('reference_id', '!=', $exceptId);
            });
        }

        if ($slugQuery->exists()) {
            return true;
        }

        $developerQuery = DeveloperProfile::query()->where('slug', $normalized);
        if ($exceptType === DeveloperProfile::class && $exceptId) {
            $developerQuery->where('id', '!=', $exceptId);
        }

        if ($developerQuery->exists()) {
            return true;
        }

        $agentQuery = AgentProfile::query()->where('slug', $normalized);
        if ($exceptType === AgentProfile::class && $exceptId) {
            $agentQuery->where('id', '!=', $exceptId);
        }

        return $agentQuery->exists();
    }

    public function isAvailable(string $slug, ?string $exceptType = null, ?int $exceptId = null): bool
    {
        if ($this->isReserved($slug)) {
            return false;
        }

        return ! $this->isTaken($slug, $exceptType, $exceptId);
    }
}
