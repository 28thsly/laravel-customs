# Laravel Customs
LaravelCustoms (LC) is a package that provides a cleaner way to import your classes and third-party packages. You can think of it this way: Composer manages your dependencies. Customs regulates the import.

LC uses a concept known as IOCA (Import Once Call Anywhere).

## Installation

```
composer require artinict/laravel-customs
```

```
{
"require": {
      "php": ">=5.3.0"
    }
}    
```

## Usage

**Typically you would do this:**

```
<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request
use App\Repository\FlightRepository;
use App\Repository\PassengersRepository as PassRepo;
use App\Services\AirAPI;
...

class FlightController extends Controller
{
  
  public function index()
  {
  
      $flight = FlightRepository::getFlightTo('Wakanda');
      $price = (new AirAPI)->getDiscountedPrice($flight, 'NGN'); 
      $user = App\User::all(); 
      //...
   
  }
  
}
```

**With LaravelCustoms, you don't have to import mulitple classes anymore. Instead, you import a single class like so:**
```
<?php

namespace App\Http\Controllers;


use Artinict\LaravelCustoms as LC;
use App\Http\Controllers\Controller; //But wait! Why is this guy still here then?🤨 [See next paragraph]

class FlightController extends Controller
{
  
  public function index()
  {
  
      $flight = LC::Repository_Flight('::getFlightTo', 'Wakanda'); //for static methods
      $price = LC::Services_AirAPI('getDiscountedPrice', $flight, 'NGN'); //for non-static methods
      $user = LC::User()::all(); 
      //...
    }
  
}
```
OK! So i'm sure you prolly wondering why we still had to write ```App\Http\Controllers\Controller;```. Well, technically, we didn't write that line of code. Laravel did that for us when we ran the command, ```php artisan make:controller FlightController```.

<p>As a rule of thumb, LaravelCustoms should not replace the preloaded classes written by Laravel.</p>

**General syntax with examples**:

> 1. LC::Prefix_Classname(['methodName'], [args]); // For non-static methods.

```E.g. LC::Services_AirAPI('getDiscountedPrice', $flight, 'NGN'); is equivalent to (new App\Services\AirAPI)->getDiscountedPrice($flight, 'NGN');```

> 2. LC::Prefix_Classname(['::methodName'], [args]); // For static methods, put ```::``` at the front of the method. 

```E.g. LC::Facades_View('::make', 'path.to.view'); is equivalent to Illuminate\Support\Facades\View::make('path.to.view');```

> 3. LC::Classname(['methodName'], [args]); 

```E.g. LC::User('getName'); is equivalent to App\User::getName();```

> 4. Finally, if you don't pass any argument, LaravelCustoms will return the class path.
```E.g. LC::User(); will return App\User```

## Customs Configuration
As you install the LaravelCustoms package, a ```cusfiguration.php``` will be created in the Laravel's ```config``` directory. This is where you would manually import your classes using aliases and prefixes (Ensure you read through the ```cusfiguration.php``` below to familiarize yourself with the convention). 

```
<?php


/*
|--------------------------------------------------------------------------
| APP NAME
|--------------------------------------------------------------------------
|
| When you run php artisan app:name, you have to come here and change it manually.
| The default base namespace is 'App'
|
*/

$APP_NAME = 'App\\';

return [

    /*
    |--------------------------------------------------------------------------
    | User-defined Imports
    |--------------------------------------------------------------------------
    |
    | This is where you should define all your local Classes, Models, Third-party Packages (See Vendor prefix below), etc.
    | Imports defined here overwrites the Laravel Customs automatic imports.Imports must be defined in the format:
    | 'Alias' => $APP_NAME\Classname::class,
    |
    | Note: User defined imports can get really messy in large Apps. Best practice is to use Prefix (See below).
    |
    */

    'User' => $APP_NAME.\User::class,


    /*
    |--------------------------------------------------------------------------
    | User-defined Imports using Prefix
    |--------------------------------------------------------------------------
    |
    | Prefixes provide a cleaner way to define your import.
    | It also helps to reduce key name conflict. Prefixes must be defined in the format:
    | 'Prefix' => [
    |                 'Alias' => $APP_NAME\Classname::class,
    |              ],
    |
    */

    'Repository' => [
    
            'Flights' => App\Repository\Flights::class,
            
    ],

    'Services' => [
    
            'AirAPI' => App\Services\AirAPI::class,

    ],

    'Packages' => [

        'Carbon' => Carbon\Carbon::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel's defined Imports
    |--------------------------------------------------------------------------
    |
    | This is where laravel's packages are defined.
    | You might almost never need to change anything here. Ensure your User-defined prefix doesn't clash with Laravels'.
    |
    */

    'Http' => [

        'Request' => Illuminate\Http\Request::class,
        'Response' => Illuminate\Http\Response::class,

    ],

    'Facades' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,

    ],

    'Support' => [

          'AggregateServiceProvider' => Illuminate\Support\AggregateServiceProvider::class,
          'Arr' => Illuminate\Support\Arr::class,
          'Carbon' => Illuminate\Support\Carbon::class,
          'Collection' => Illuminate\Support\Collection::class,
          'Composer' => Illuminate\Support\Composer::class,
          'Fluent' => Illuminate\Support\Fluent::class,
          'HigherOrderCollectionProxy' => Illuminate\Support\HigherOrderCollectionProxy::class,
          'HigherOrderTapProxy' => Illuminate\Support\HigherOrderTapProxy::class,
          'HtmlString' => Illuminate\Support\HtmlString::class,
          'InteractsWithTime' => Illuminate\Support\InteractsWithTime::class,
          'Manager' => Illuminate\Support\Manager::class,
          'MessageBag' => Illuminate\Support\MessageBag::class,
          'NamespacedItemResolver' => Illuminate\Support\NamespacedItemResolver::class,
          'Optional' => Illuminate\Support\Optional::class,
          'Pluralizer' => Illuminate\Support\Pluralizer::class,
          'ProcessUtils' => Illuminate\Support\ProcessUtils::class,
          'ServiceProvider' => Illuminate\Support\ServiceProvider::class,
          'Str' => Illuminate\Support\Str::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | LaravelCustoms Aliases & Packages
    |--------------------------------------------------------------------------
    |
    | This is where LaravelCustoms aliases and packages are defined.
    | DO NOT OVERWRITE.
    |
    */

    'LC_APP_NAMESPACE' => $APP_NAME,

];

```

> Note: LC_APP_NAMESPACE is a reserved key name and MUST NOT be overwritten.

**Automatic Resolve**<br/>
If you call a class that you didn't import in the ```cusfiguration.php```. LaravelCustoms will automatically scan through the ```app\``` directory to find the class and import it on the fly. An exception would be thrown if the class doesn't exists. 

> This is not a recommended approach especially on large projects.

## Credit
Sadiq Lukman, Artinict. <br/>
https://twitter.com/28thsly

## License

The LaravelCustoms library is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

