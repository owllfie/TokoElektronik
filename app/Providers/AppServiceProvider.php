<?php

namespace App\Providers;

use App\Support\PageAccessManager;
use App\Support\WebSettingManager;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view): void {
            $roleId = (int) data_get(request()->session()->get('user'), 'role', 0);
            $settings = WebSettingManager::all();

            $view->with('sharedCompanyName', $settings['company_name'] ?? 'Electro');
            $view->with('sharedCompanyMark', $settings['company_mark'] ?? 'E');
            $view->with('accessiblePageKeys', PageAccessManager::allowedPageKeysForRole($roleId));
        });
    }
}
