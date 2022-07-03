<?php

namespace Modules\FetchSeen\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

// define
define('FSEEN_MODULE', 'fetchseen');

class FetchSeenServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->hooks();
    }

    /**
     * Module hooks.
     */
    public function hooks()
    {
        \Eventy::addFilter('fetch_emails.unseen', function($unseen, $mailbox) {
            \Log::error('FETCH SEEN:'.$mailbox->fetch_seen);


            return $mailbox->fetch_seen ? false : true;
        }, 10, 2);

        \Eventy::addAction('mailbox.connection_incoming.after_default_settings', function($mailbox) {
            echo \View::make(FSEEN_MODULE.'::setting', ['mailbox' => $mailbox])->render();
        }, 10);
        
        \Eventy::addAction('mailbox.incoming_settings_before_save', function($mailbox, $request) {
            if (auth()->user()->isAdmin()) {
                $mailbox->fetch_seen = $request->fetch_seen ? 1 : 0;
            }
        }, 10, 2);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTranslations();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('fetchseen.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'fetchseen'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/fetchseen');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/fetchseen';
        }, \Config::get('view.paths')), [$sourcePath]), 'fetchseen');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $this->loadJsonTranslationsFrom(__DIR__ .'/../Resources/lang');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
