<?php
declare(strict_types=1);

namespace VDauchy\RoutingExtender;

use Illuminate\Routing\UrlGenerator as RoutingUrlGenerator;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class UrlGenerator extends RoutingUrlGenerator
{
    /**
     * @param RoutingUrlGenerator $parentUrlGenerator
     * @return UrlGenerator
     */
    public static function extends(RoutingUrlGenerator $parentUrlGenerator): UrlGenerator {
        return tap(
            new static($parentUrlGenerator->routes, $parentUrlGenerator->request, $parentUrlGenerator->assetRoot),
            function(UrlGenerator $urlGenerator) use ($parentUrlGenerator) {
                foreach (get_object_vars($parentUrlGenerator) as $name => $value) {
                    $urlGenerator->$name = $value;
                }
            }
        );
    }

    /**
     * @inheritDoc
     */
    protected function routeUrl(): RouteUrlGenerator
    {
        if (! $this->routeGenerator) {
            $this->routeGenerator = new RouteUrlGenerator($this, $this->request);
        }

        return $this->routeGenerator;
    }

    /**
     * @inheritDoc
     */
    public function route($name, $parameters = [], $absolute = true): string
    {
        if (static::hasMacro('customRouteResolver') && $route = $this->customRouteResolver($name, $parameters, $absolute)) {
            return $this->toRoute($route, $parameters, $absolute);
        }

        if (is_string($name)) {
            return parent::route($name, $parameters, $absolute);
        }

        throw new RouteNotFoundException("Passed name of type:" . gettype($name) . " not handled: " . json_encode($name));
    }
}
