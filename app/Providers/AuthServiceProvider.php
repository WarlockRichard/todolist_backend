<?php

namespace App\Providers;

use App\Policies\ToDoPolicy;
use App\Policies\UserTodosPolicy;
use App\Todo;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//        'App\Model' => 'App\Policies\ModelPolicy',
        ToDO::class => ToDOPolicy::class,
        User::class => UserTodosPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

//        Gate::define('index', 'UserTodosPolicy@view');
//        Gate::define('modify', 'UserTodosPolicy@modify');
    }
}
