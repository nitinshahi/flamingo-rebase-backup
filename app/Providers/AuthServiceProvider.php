<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\GlobalSetting;
use App\Policies\API\V1\Admin\GlobalSettingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        GlobalSetting::class => GlobalSettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
