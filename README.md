# Footprint

A laravel package viewing clients IPs, browser information and System Operating System.

[![Footprint](https://res.cloudinary.com/zlayit/image/upload/v1591554804/Screen_Shot_2020-06-07_at_7.22.11_PM_wser52.png =350x)]


*Note:* at this point I would strongly recommend use of route names.

To log view you can use the Footprint static logTrail method passing request object as parameter
```php
<?php

use Footprint;

// log trail
public function index(Request $request){
	Footprint::logTrail($request);
	return view('welcome');
}

// log trail with description
public function task(Request $request){
	$request->description = "Task manager";
	Footprint::logTrail($request);
	return view('task');
}

```

## Installation

### With Composer

```
$ composer require codedreamer/footprint
```

```json
{
    "require": {
        "codedreamer/footprint": "^1.0.0"
    }
}
```

```
$ composer install
```



```php
<?php
 'providers' => [
    App\Providers\RouteServiceProvider::class,
    Codedreamer\Footprint\FootprintServiceProvider::class,
    ..................
 ]

 'aliases' => [
    'View' => Illuminate\Support\Facades\View::class,
    'Footprint' => Codedreamer\Footprint\Facades\Footprint::class,
    ..................
 ]

```

## Docs

To log every incoming authentication request add the Footprint static class inside the authenticate request middleware class
```php
<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Footprint;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // log trail
        Footprint::logTrail($request);

        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}

```


To log user login / register add the following line to Auth\LoginController or Auth\RegisterController
```php
<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Footprint;

class LoginController extends Controller
{
    // an example
    protected function login($request)
    {
        // log trail
        Footprint::logTrail($request);

        // code here...
    }
}

```

## Security contact information

Coming soon

## Credits

Coming soon

### Contributors

This project exists thanks to all the people who contribute. 

Coming soon

### Translators

Work in progress

### Backers

Thank you to all our backers! 🙏  become a backer and get your image tag to this package

### Sponsors

Support this project by becoming a sponsor. Your logo will show up here with a link to your website.
