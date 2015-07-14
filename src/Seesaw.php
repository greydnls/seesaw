<?php namespace Kayladnls\Seesaw;

use FastRoute\BadRouteException;

class Seesaw
{
    protected $namedRoutes = [];
    protected $base_url = null;
    private $router;


    public function __construct($router = null)
    {
        $this->router = ($router) ?: new RouteCollection();
    }

    public function __call($method, $args)
    {
        if (method_exists($this->router, $method)) {
            return call_user_func_array(array($this->router, $method), $args);
        }
    }

    public function getBaseUrl()
    {
        return $this->base_url;
    }

    public function setBaseUrl($url)
    {
        $this->base_url = $url;
    }

    public function route($name, $parameters = array())
    {
        if (isset($this->namedRoutes[$name])) {
            $route = $this->namedRoutes[$name];
        }
        else if ($this->inferRoute($name) !== false) {
            $route = $this->inferRoute($name);
        }
        else {
            throw new BadRouteException('Route Not Found');
        }

        return Route::reverse($route, $this->base_url, $parameters);
    }

    private function inferRoute($name)
    {
        $named_route = $this->parseCamel($name);

        $registered_routes = $this->router->getData()[0];

        if (isset($registered_routes[$named_route])) {
            return $named_route;
        }
        else {
            return false;
        }
    }

    private function parseCamel($class_name)
    {
        $return = [];

        foreach (str_split($class_name) as $i => $letter) {
            $return[] = (ord($letter) > 64 && ord($letter) < 91) ? '/' . strtolower($letter) : strtolower($letter);
        }

        return implode('', $return);
    }

    /**
     * Add a named route to the collection
     */
    public function addNamedRoute($name, $method, $route, $handler, $strategy = null)
    {
        $verb = strtolower($method);
        $route = Route::$verb($route, $handler);

        $this->namedRoutes[$name] = $route->getUrl();


        $this->router->add($route);
    }
}
