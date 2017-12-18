<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies();

        $gate->before(function($user, $ability) use($gate) {

            if ($user->isAdmin()) {
                return true;
            }
            /*
            return $user->hasPermission($ability);
            */
            $gate->define($ability, function ($user, $arguments=null) use($ability) {

                if ($ability=='profile' and
                    !is_null($arguments) and
                    $arguments==$user->id) {
                    return true;
                }
                return $user->hasPermission($ability);
            });
        });

    }
}
