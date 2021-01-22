<?php
declare(strict_types=1);

namespace VDauchy\RoutingExtender;

use Illuminate\Routing\RouteUrlGenerator as RoutingRouteUrlGenerator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RouteUrlGenerator extends RoutingRouteUrlGenerator
{
    /**
     * @inheritDoc
     */
    protected function replaceRouteParameters($path, array &$parameters)
    {
        $path = $this->replaceNamedParameters($path, $parameters);

        $path = preg_replace_callback('/\{.*?\}/', function ($match) use (&$parameters) {
            // Reset only the numeric keys...
            $parameters = array_merge($parameters);

            return (! isset($parameters[0]) && ! Str::endsWith($match[0], '?}'))
                ? $match[0]
                : Arr::pull($parameters, 0, '####NO_PARAMETER_FOUND####');
        }, $path);

        $path = str_replace(['/####NO_PARAMETER_FOUND####/', '####NO_PARAMETER_FOUND####'], ['/', ''], $path);

        return trim(preg_replace('/\{.*?\?\}/', '', $path), '/');
    }
}