<?php namespace Kayladnls\Seesaw;

use FastRoute\BadRouteException;
use League\Container\Container;

/**
 * @method RouteCollection add(Route $route)
 * @method RouteCollection addGroup(RouteGroup $group)
 * @method RouteGroup[] getGroups()
 * @method bool routeIsRegistered($route)
 * @method Route|bool findByAction($action)
 * @method RouteGroup|bool findGroupByRoute($route)
 */
class Seesaw
{
    /**
     * @var array
     */
    protected $namedRoutes = [];

    /**
     * @var string
     */
    protected $base_url = null;

    /**
     * @var RouteCollection
     */
    private $router;


    /**
     * @param RouteCollection $router
     * @param string $base_url
     * @param Container $container
     */
    public function __construct(RouteCollection $router = null, $base_url = null, $container)
    {
        $this->router = ($router) ?: new RouteCollection($container);
        $this->base_url = $base_url;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return Route
     * @throws BadRouteException
     */
    public function outputRoute($name, $parameters = [])
    {
        if (isset($this->namedRoutes[$name])) {
            $route = $this->namedRoutes[$name];
        } elseif ($this->inferRoute($name) !== false) {
            $route = $this->inferRoute($name);
        } else {
            throw new BadRouteException('Route Not Found or Does Not have Name');
        }

        return Route::get($route, null, $this->base_url, $parameters);
    }

    /**
     * Add a named route to the collection
     *
     * @param string $name
     * @param string $method
     * @param Route $route
     * @param string $handler
     */
    public function addNamedRoute($name, $method, $route, $handler)
    {
        $verb = strtolower($method);

        /* @var Route $route */
        $route = Route::$verb($route, $handler);

        $this->namedRoutes[$name] = $route->getUrl();

        $this->router->add($route);
    }

    /**
     * @param string $name
     * @return bool|string
     */
    private function inferRoute($name)
    {
        $named_route = $this->parseCamel($name);

        $registered_routes = $this->router->getData()[0];

        if (isset($registered_routes[$named_route])) {
            return $named_route;
        }

        return false;

    }

    /**
     * @param string $class_name
     * @return string
     */
    private function parseCamel($class_name)
    {
        $return = [];

        foreach (str_split($class_name) as $i => $letter) {
            $return[] = (ord($letter) > 64 && ord($letter) < 91) ? '/' . strtolower($letter) : strtolower($letter);
        }

        return implode('', $return);
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (method_exists($this->router, $method)) {
            return call_user_func_array([$this->router, $method], $args);
        }
    }
}
