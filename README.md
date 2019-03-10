# Laravel Customs
LaravelCustoms (LC) is a package that provides a cleaner way to import your classes and third-party packages. You can think of it this way: Composer manages your dependencies. Customs regulates the import.

LC uses a concept known as IOCA (Import Once Call Anywhere) to import and call classes.

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
use App\Services\Price;
use App\Models\Flight;
...

class FlightController extends Controller
{
  
  public function index()
  {
  
      $flight = FlightRepository::getFlightTo('Wakanda');
      $price = (new Price)->getDiscountedPrice($flight, 'NGN'); 
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
use App\Http\Controllers\Controller; //Then why is this guy still here then?🤨 [See next paragraph]

class FlightController extends Controller
{
  
  public function index()
  {
  
      $flight = LC::FlightRepository('::getFlightTo', 'Wakanda');
      $price = LC::Price('getDiscountedPrice', $flight, 'NGN');
      $user = LC::User()::all(); 
      //...
    }
  
}
```
OK! So i'm sure you prolly wondering why we still had to write ```App\Http\Controllers\Controller;```. Well, technically, we didn't write that line of code. Laravel did that for us when we ran the command, ```php artisan make:controller FlightController```.
As a rule of thumb, LaravelCustoms should not replace the preloaded classes written by Laravel.

## Credit
Sadiq Lukman, Artinict
https://twitter.com/28thsly

## License

The LaravelCustoms library is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

