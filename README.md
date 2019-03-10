<p align="center"><img src="http://leedder.com/display_image/reformed/5c847c7d92f23_7119356000.png/150/150/1" alt="Laravel Customs Logo"/></p>

# Laravel Customs
LaravelCustoms (LC) is a package that provides a cleaner way to import your classes and third-party packages. You can think of it this way: Composer manages your dependencies, Customs regulates your imports.

LC uses a concept known as IOCA (Import Once Call Anywhere). 

> And if you don't want to use this concept, LC will automatically import the class on the fly. (See "Automatic Resolving" below)

## Table of Contents
1. <a href="https://github.com/28TH/laravel-customs/blob/master/README.md#installation">Installation</a>
2. <a href="https://github.com/28TH/laravel-customs/blob/master/README.md#usage">Usage</a>
3. <a href="https://github.com/28TH/laravel-customs/blob/master/README.md#import-once-call-anywhere-ioca">Import Once Call Anywhere (IOCA)</a>
4. <a href="https://github.com/28TH/laravel-customs/blob/master/README.md#customs-configuration">Customs Configuration</a>
5. <a href="https://github.com/28TH/laravel-customs/blob/master/README.md#credits">Credits</a>
6. <a href="https://github.com/28TH/laravel-customs/blob/master/README.md#license">License</a>

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
  
  public function index(Request $request)
  {
  
      $flight = FlightRepository::getFlightTo('Wakanda');
      $price = (new AirAPI)->getDiscountedPrice($flight, 'NGN'); 
      $user = App\User::all(); 
      
      $destination = $request->dest;

      //...
   
  }
  
}
```

**But now, with LaravelCustoms, you don't have to import mulitple classes anymore. Instead, you import a single class like so:**
```
<?php

namespace App\Http\Controllers;


use Artinict\LaravelCustoms as LC;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request
/*But wait! Why did we still import these guys?🤨 [See next paragraph]*/

class FlightController extends Controller
{
  
  public function index(Request $request)
  {
  
      $flight = LC::Repository_Flight('::getFlightTo', 'Wakanda'); //for static methods
      $price = LC::Services_AirAPI('getDiscountedPrice', $flight, 'NGN'); //for non-static methods
      $user = LC::User()::all();
      
      $destination = $request->dest;

      //...
    }
  
}
```
OK! So i'm sure you prolly wondering why we still had to write ```use App\Http\Controllers\Controller;```. Well, technically, we didn't write that line of code. Laravel did that for us when we ran the command, ```php artisan make:controller FlightController```.

<p>As a rule of thumb, LaravelCustoms should not replace the preloaded classes written by Laravel.</p>

Ok! So what about ```use Illuminate\Http\Request```???! Unfortunately, LC cannot support Dependency Injection (DI) as at v1.0.😬

**General usage with examples**:

1. **For non-static methods:**<br/> LC::Prefix_Classname(['methodName'], [args]);

```
Example:
LC::Services_AirAPI('getDiscountedPrice', $flight, 'NGN');

is equivalent to:
(new App\Services\AirAPI)->getDiscountedPrice($flight, 'NGN');

```

2. **For static methods, put ```::``` at the front of the methodName:**<br/>LC::Prefix_Classname(['::methodName'], [args]); 

```
Example:
LC::Facades_View('::make', 'path.to.view'); or LC::Facades_View()::make('path.to.view');

is equivalent to:
Illuminate\Support\Facades\View::make('path.to.view');

```

3. **Calling a non-static method without "Prefix":**<br/>LC::Classname(['methodName'], [args]); 

```
Example:
LC::User('getName');

is equivalent to:
(new App\User)->getName();

```

4. **To get a non-static property of a class:**<br/> $classInstance = LC::Classname(); <br/> $property = (new $classInstance)->propertyName; 

```
Example: 
$flight = LC::Flight();
$property = (new $flight)->category;
     
is equivalent to:
$property = (new App\Flight)->$category;

/*Honestly, i hardly use LC to reach for non-static properties😁*/

```

5. **To get a static property of a class:**<br/> LC::Classname()::$staticProperty; 

```
Example:
LC::Flight()::$category;

is equivalent to:
App\Flight::$category;

```

6. **Finally, if you don't pass any argument, LaravelCustoms will return the class path.**
```
Example:
LC::User(); 

will return App\User

```

## Import Once Call Anywhere (IOCA)
One of the objectives of LaravelCustoms is to get rid of having to repeatedly import classes everytime you need them. LC provides a cleaner way to do this by using a new concept known as IOCA (Import Once Call Anywhere).

In the customs configuration file provided  (See next section). Define your imports like so:

**Import using Alias** 

Alias is a simple way to import classes. This is perfect for your Models and other classes that are likely not to have a classname clash. 

```
[
      "Alias" => Namespace\Classname::class
],

```

For example, ```App\UserFlightModel.php``` can be imported as:
```
[
      "UFModel" => App\UserFlight::class
],

``` 

And this would be called as ```LC::UFModel();```.

**Import using prefix**

Prefix is a cleaner way to import classes. It groups a set of classes into a category (called Prefix). This helps to mitigate classname clashes.

```
[
      "Prefix" => [
      
            "Alias" => Namespace\Classname::class,  
      ],
],

```
   
For example, ```App\Repository\FlightRepository.php``` can be imported as:

```
[
      "Repo" => [
      
            "Flight" => App\Repository\Flight::class,
      
      ],
   ],

```

And this would be called as ```LC::Repo_Flight();```.

## Customs Configuration

When you install the LaravelCustoms package, a ```cusfiguration.php``` will be created in the Laravel's ```config``` directory. This is where you would manually import your classes using aliases and prefixes (Ensure you read through the ```cusfiguration.php``` below to familiarize yourself with the convention). 

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

**Automatic Resolving**<br/>
If you call a class that you didn't import in the ```cusfiguration.php```. LaravelCustoms will automatically scan through the ```app\``` directory to find the class and import it on the fly. An exception would be thrown if the class doesn't exists. 

> This is not a recommended approach especially on large projects.

## Credits
Sadiq Lukman, Artinict. <br/>
https://twitter.com/28thsly

## License

The LaravelCustoms library is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

