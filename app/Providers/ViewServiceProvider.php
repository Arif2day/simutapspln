<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notifications;

use Sentinel;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            if (Sentinel::check()) {
                $user = Sentinel::getUser();
    
                $unreadCount = \App\Models\Notifications::where('notifiable_type', \App\Models\Users::class)
                    ->where('notifiable_id', $user->id)
                    ->whereNull('read_at')
                    ->count();
                $unread = \App\Models\Notifications::where('notifiable_type', \App\Models\Users::class)
                ->where('notifiable_id', $user->id)
                ->whereNull('read_at')
                ->get();
                
                $view->with('unreadNotifCount', $unreadCount)->with('unread',$unread);
            }
        });
    }
}
