<?php

namespace Botble\RealEstate\Enums;

use Botble\Base\Supports\Enum;

/**
 * @method static PgCategoryEnum STUDENT_PG()
 * @method static PgCategoryEnum WORKING_PROFESSIONAL_PG()
 * @method static PgCategoryEnum FAMILY_PG()
 * @method static PgCategoryEnum LADIES_PG()
 * @method static PgCategoryEnum GENTS_PG()
 * @method static PgCategoryEnum COLIVING_SPACE()
 */
class PgCategoryEnum extends Enum
{
    public const STUDENT_PG = 'student_pg';

    public const WORKING_PROFESSIONAL_PG = 'working_professional_pg';

    public const FAMILY_PG = 'family_pg';

    public const LADIES_PG = 'ladies_pg';

    public const GENTS_PG = 'gents_pg';

    public const COLIVING_SPACE = 'coliving_space';

    public static $langPath = 'plugins/real-estate::property.pg_categories';
}
