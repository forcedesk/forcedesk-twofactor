<?php namespace Northfire\TwoFactor;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class TwoFactorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        // Define Routes

        $routeConfig = ['namespace' => 'App\Http\Controllers', 'prefix' => '/mfa/'];

        $this->app[ 'router' ]->group($routeConfig, function(Router $router) {
            
            $router->get('register', [
                'uses' => 'TwoFactorController@get2faRegister',
                'as' => 'authenticator.register',
                'middleware' => ['web','auth']
            ]);
            $router->post('register', [
                'uses' => 'TwoFactorController@post2faComplete',
                'as' => 'authenticator.complete',
                'middleware' => ['web','auth']
            ]);

            $router->get('verify', [
                'uses' => 'TwoFactorController@get2faManual',
                'as' => 'authenticator.verify',
                'middleware' => ['web','auth']
            ]);
            $router->post('verify', [
                'uses' => 'TwoFactorController@post2faManual',
                'as' => 'authenticator.verify.post',
                'middleware' => ['web','auth']
            ]);
            
        });

        // Publish Vendor Files.

        $this->publishes([
            __DIR__.'/../migrations/' => base_path('/database/migrations')
        ], 'twofactor-migrations');

        $this->publishes([
            __DIR__.'/../views' => base_path('/resources/views'),
        ], 'twofactor-views');

        $this->publishes([
            __DIR__.'/../controllers' => base_path('/app/Http/Controllers'),
        ], 'twofactor-controllers');

        $this->publishes([
            __DIR__.'/../middleware' => base_path('/app/Http/Middleware'),
        ], 'twofactor-middleware');

        $this->publishes([
            __DIR__.'/../config' => base_path('/config'),
        ], 'twofactor-config');

        $this->registerResources();

    }

        /**
     * Register other package's resources.
     *
     * @return void
     */
    private function registerResources()
    {
        // Nothing to register here.
    }

}
