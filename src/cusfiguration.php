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


    ],

    'Services' => [


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
