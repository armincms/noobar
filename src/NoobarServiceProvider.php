<?php 

namespace Armincms\Noobar;
  
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova as LaravelNova;
use Armincms\Snail\Events\ServingSnail;
use Armincms\Snail\Snail;


class NoobarServiceProvider extends ServiceProvider 
{   
    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {    
    	$this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');
    	$this->loadMigrationsFrom(__DIR__.'/../database/migrations'); 

      \Config::set('imager.schemas.logo', [
          'group'         => '*', // group of usage
          'name'          => 'logo', // unique name
          'resize'        => 'crop', // resize type
          'width'         => 150,
          'height'        => 150,
          'position'      => 'center', // crop postiion anchor
          'upsize'        => false, // cutting type
          'compress'      => 75,
          'extension'     => null, // save extension
          'placeholder'   => image_placeholder(150, 150),
      ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {  
    	LaravelNova::serving([$this, 'servingNova']);
    	Snail::serving([$this, 'servingSnail']);
    } 

    /**
     * Register any Nova services.
     * 
     * @return void
     */
    public function servingNova()
    {
    	LaravelNova::resources([
    		Nova\Slide::class
    	]);
    }

    /**
     * Register any Snail services.
     * 
     * @return void
     */
    public function servingSnail()
    {
    	Snail::version('1.0.0', function($snail) { 
            $snail::resources([
                Slide::class,
            ]);
        });
    } 
}
