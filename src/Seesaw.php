<?php namespace Kayladnls\Seesaw;

use FastRoute\BadRouteException;

class Seesaw
{
    /**
     * @var array
     */
    protected $namedRoutes = [];

    /**
     * @var null
     */
    protected $base_url = null;

    /**
     * @var RouteCollection
     */
    private $router;


    public function __construct(RouteCollection $router = null, $base_url = null, $container)
    {
        $this->router = ($router) ?: new RouteCollection($container);
        $this->base_url = $base_url;
    }

    public function getBaseUrl()
    {
        return $this->base_url;
    }

    public function route($name, $parameters = [])
    {
        if (isset($this->namedRoutes[$name])) {
            $route = $this->namedRoutes[$name];
        } else {
            if ($this->inferRoute($name) !== false) {
                $route = $this->inferRoute($name);
            } else {
                throw new BadRouteException('Route Not Found');
            }
        }

        return Route::get($route, null, $this->base_url, $parameters);
    }

    /**
     * Add a named route to the collection
     */
    public function addNamedRoute($name, $method, $route, $handler)
    {
        $verb = strtolower($method);
        $route = Route::$verb($route, $handler);

        $this->namedRoutes[$name] = $route->getUrl();

        $this->router->add($route);
    }


    private function inferRoute($name)
    {
        $named_route = $this->parseCamel($name);

        $registered_routes = $this->router->getData()[0];

        if (isset($registered_routes[$named_route])) {
            return $named_route;
        }

        return false;

    }

    private function parseCamel($class_name)
    {
        $return = [];

        foreach (str_split($class_name) as $i => $letter) {
            $return[] = (ord($letter) > 64 && ord($letter) < 91) ? '/' . strtolower($letter) : strtolower($letter);
        }

        return implode('', $return);
    }

    public function __call($method, $args)
    {
        if (method_exists($this->router, $method)) {
            return call_user_func_array([$this->router, $method], $args);
        }
    }
}
