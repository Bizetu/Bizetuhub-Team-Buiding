<?php

namespace A17\Twill\Models\Enums;

use MyCLabs\Enum\Enum;

class UserRole extends Enum
{
    /**
     * @var string
     */
    public const Learner = 'Learner';

    /**
     * @var string
     */
    public const COMPANYHR = 'Company HR';

    /**
     * @var string
     */
    public const ADMIN = 'Admin';

    /**
     * @var string
     */
    public const SUPERADMIN = 'SUPERADMIN';

}
