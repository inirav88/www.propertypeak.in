<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyAnalytics extends BaseModel
{
    protected $table = 're_property_analytics';

    protected $fillable = [
        'property_id',
        'event_type',
        'user_ip',
        'user_agent',
        'referrer',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public static function track(int $propertyId, string $eventType): void
    {
        static::create([
            'property_id' => $propertyId,
            'event_type' => $eventType,
            'user_ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referrer' => request()->header('referer'),
        ]);
    }

    public static function getPropertyStats(int $propertyId): array
    {
        $stats = static::where('property_id', $propertyId)
            ->selectRaw('event_type, COUNT(*) as count')
            ->groupBy('event_type')
            ->pluck('count', 'event_type')
            ->toArray();

        return [
            'views' => $stats['view'] ?? 0,
            'contacts' => $stats['contact'] ?? 0,
            'phone_clicks' => $stats['phone_click'] ?? 0,
            'email_clicks' => $stats['email_click'] ?? 0,
            'whatsapp_clicks' => $stats['whatsapp_click'] ?? 0,
            'total' => array_sum($stats),
        ];
    }
}
