<?php

use App\Providers\AppServiceProvider;
use Modules\Activity\ActivityServiceProvider;
use Modules\Finance\FinanceServiceProvider;
use Modules\Letter\LetterServiceProvider;
use Modules\LetterCategory\LetterCategoryServiceProvider;
use Modules\Meeting\MeetingServiceProvider;
use Modules\Member\MemberServiceProvider;
use Modules\Organization\OrganizationServiceProvider;
use Modules\Role\RoleServiceProvider;
use Modules\User\UserServiceProvider;
use Modules\UserAccess\UserAccessServiceProvider;

return [
    AppServiceProvider::class,
    OrganizationServiceProvider::class,
    MemberServiceProvider::class,
    RoleServiceProvider::class,
    UserAccessServiceProvider::class,
    UserServiceProvider::class,
    ActivityServiceProvider::class,
    MeetingServiceProvider::class,
    FinanceServiceProvider::class,
    LetterCategoryServiceProvider::class,
    LetterServiceProvider::class,
];
