<?php
declare(strict_types=1);

namespace VDauchy\RoutingExtender;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;
use Illuminate\Routing\UrlGenerator as RoutingUrlGenerator;

class ServiceProvider extends SupportServiceProvider
{
    public function register() {

        Router::macro('optionalParameterGroup', function (string $parameter, string $pattern, array $attributes, $routes) {
            $this->group($attributes, function (Router $router) use ($parameter, $pattern, $routes) {
                $router->group([
                    'prefix'=> '{'.$parameter.'?}',
                    'where' => [$parameter => $pattern],
                ], $routes);
                $router->group([
                    'as'    => '[default:'.$parameter.']'
                ], $routes);
            });
        });

        $this->app->extend('url', fn(RoutingUrlGenerator $url): UrlGenerator => UrlGenerator::extends($url));
    }
}