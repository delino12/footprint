# Footprint

A laravel package viewing clients IPs, browser information and System Operating System.


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

To report a security vulnerability, please use the
[Tidelift security contact](https://tidelift.com/security).
Tidelift will coordinate the fix and disclosure.

## Credits

### Contributors

This project exists thanks to all the people who contribute. 

<a href="https://github.com/briannesbitt/Carbon/graphs/contributors" target="_blank"><img src="https://opencollective.com/Carbon/contributors.svg?width=890&button=false" /></a>

### Translators

[Thanks to people helping us to translate Footprint in so many languages](https://carbon.nesbot.com/contribute/translators/)

### Backers

Thank you to all our backers! 🙏 [[Become a backer](https://opencollective.com/Carbon#backer)]

<a href="https://opencollective.com/Carbon#backers" target="_blank"><img src="https://opencollective.com/Carbon/backers.svg?width=890"></a>

### Sponsors

Support this project by becoming a sponsor. Your logo will show up here with a link to your website. [[Become a sponsor](https://opencollective.com/Carbon#sponsor)]
<a href="https://opencollective.com/Carbon/sponsor/0/website" target="_blank"><img src="https://opencollective.com/Carbon/sponsor/0/avatar.svg"></a>
