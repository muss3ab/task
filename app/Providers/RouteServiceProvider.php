<?
namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot()
    {
        // Set any pattern constraints for route parameters, if needed
        $this->configureRateLimiting();

        // Load routes based on groups (web, api)
        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        Route::bind('post', function ($value) {
            return \App\Models\Post::where('id', $value)->firstOrFail();
        });
        
        Route::bind('user', function ($value) {
            return \App\Models\User::where('id', $value)->firstOrFail();
        });

        Route::bind('tag', function ($value) {
            return \App\Models\Tag::where('id', $value)->firstOrFail();
        });

        // Example of a global pattern constraint for 'id' parameters
        Route::pattern('id', '[0-9]+');
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
?>