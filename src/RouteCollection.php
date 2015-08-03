<?php namespace Kayladnls\Seesaw;

use League\Container\Container;
use League\Route\RouteCollection as Router;

class RouteCollection extends Router
{
    /**
     * @var Route[]
     */
    protected $route_objects = [];

    /**
     * @var RouteGroup[]
     */
    protected $groups = [];

    public function __construct($container = null)
    {
        if ($container == null) {
            $container = new Container();
        }
        parent::__construct($container);
    }

    /**
     * @param Route $route
     */
    public function add(Route $route)
    {
        parent::addRoute($route->getVerb(), $route->getUrl(), $route->getAction());
        $this->route_objects[] = $route;

        return $this;
    }

    /**
     * @param RouteGroup $group
     */
    public function addGroup(RouteGroup $group)
    {
        $group->updateRoutes();

        foreach ($group->getRoutes() as $route) {
            $this->route_objects[] = $route;
            $this->add($route);
        }
        $this->groups[] = $group;

        return $this;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function routeIsRegistered($route)
    {
        foreach ($this->route_objects as $route_object) {
            if ($route->getUrl() == $route_object->getUrl()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $action
     * @return Route
     */
    public function findByAction(array $action)
    {
        foreach ($this->route_objects as $route) {
            if ($route->getAction() == implode('::', $action)) {
                return $route;
            }
        }

        return false;
    }

    /**
     * @param Route $route
     * @return RouteGroup
     */
    public function findGroupByRoute(Route $route)
    {
        foreach ($this->groups as $group) {
            if ($group->includes($route)) {
                return $group;
            }
        }

        return false;
    }
}
