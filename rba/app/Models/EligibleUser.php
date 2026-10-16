<?php

namespace App\Models;

use Carbon\Carbon;
use A17\Twill\Models\User as TwillUser;
use Illuminate\Support\Facades\Auth;

class EligibleUser extends TwillUser
{
    protected $table = 'twill_users';

    public const PASS_MARK = 0.8;

    protected static function booted()
    {
        static::addGlobalScope('eligible', function ($builder) {
            $builder->where('average_score', '>=', EligibleUser::PASS_MARK);
        });

        static::addGlobalScope('company_filter', function ($builder) {
            $user = Auth::user();

            if (auth()->user()->role=="COMPANYHR") {
                $builder->where('twill_users.company_id', $user->company_id);
            }
        });
    }
}