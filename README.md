**Laravel Routing Extender**

This package extends Laravel's core routing services.

- Add support for optional parameters in the middle of routes using the macro `optionalParameterGroup` as:

```php
// In Routes/web.php:
Route::optionalParameterGroup('hreflang', '^[a-z]{2}(?:\-[a-z]{2})?$', ['as' => 'Hreflang::'], function () {
    Route::get('home')->name('home');
});

// In YourCode.php:
route('Hreflang::home', []); // Will return `/home`
route('Hreflang::home', ['hreflang' => 'en-us']); // Will return `/en-us/home`
```

- Add support for custom route resolver in UrlGenerator by adding the macro `customRouteResolver` as.

```php
// In xxxProvider.php
URL::macro('customRouteResolver', function($name, $parameters, $absolute): ?\Illuminate\Routing\Route {
    if ($name instanceof CustomClassA::class) {
        return $name->getRoute();
    }
    if ($name instanceof CustomClassB::class) {
        return $name->generateRoute();
    }
    return null;
});

// In YourCode.php:
route($instanceClassA); // Will generate the url from the Route object return by `$instanceClassA->getRoute()`
route($instanceClassB); // Will generate the url from the Route object return by `$instanceClassB->generateRoute()`
```