<?php

namespace Botble\RealEstate\Enums;

use Botble\Base\Supports\Enum;

/**
 * @method static PgOccupancyTypeEnum SINGLE()
 * @method static PgOccupancyTypeEnum DOUBLE()
 * @method static PgOccupancyTypeEnum TRIPLE()
 * @method static PgOccupancyTypeEnum FOUR_SHARING()
 * @method static PgOccupancyTypeEnum DORMITORY()
 */
class PgOccupancyTypeEnum extends Enum
{
    public const SINGLE = 'single';

    public const DOUBLE = 'double';

    public const TRIPLE = 'triple';

    public const FOUR_SHARING = 'four_sharing';

    public const DORMITORY = 'dormitory';

    public static $langPath = 'plugins/real-estate::property.pg_occupancy_types';
}
