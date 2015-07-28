<?php namespace Kayladnls\Seesaw;

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
        }
        $this->groups[] = $group;

        return $this;
    }

    public function getGroups()
    {
        return $this->groups;
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
