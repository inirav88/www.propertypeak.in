<?php

namespace Botble\RealEstate\Enums;

use Botble\Base\Supports\Enum;

/**
 * @method static PgPricingModelEnum PER_BED()
 * @method static PgPricingModelEnum PER_ROOM()
 * @method static PgPricingModelEnum BOTH()
 */
class PgPricingModelEnum extends Enum
{
    public const PER_BED = 'per_bed';

    public const PER_ROOM = 'per_room';

    public const BOTH = 'both';

    public static $langPath = 'plugins/real-estate::property.pg_pricing_models';
}
