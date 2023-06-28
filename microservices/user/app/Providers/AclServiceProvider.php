<?php

namespace App\Providers;

use App\Models\Permission;
use Gate;
use Illuminate\Support\ServiceProvider;
use Schema;

class AclServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }


    public function boot(): void
    {
        if(env('APP_ENV') !== 'build' and Schema::hasTable('permissions'))
        {
            Permission::all()->each(
                fn($permission) =>
                Gate::define($permission->name, fn($user) => $user->hasPermission($permission->name))
            );
        }
    }
}
