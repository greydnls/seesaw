<?php namespace Kayladnls\Seesaw;

class RouteGroup
{
    /**
     * @var Route[]
     */
    protected $routes = [];

    /**
     * @var string
     */
    protected $url_segment;

    /**
     * @var RouteGroup[]
     */
    protected $groups = [];

    /**
     * @param Route[] $routes
     * @param string $url_segment
     */
    public function __construct(array $routes = [], $url_segment = null)
    {
        $this->routes = $routes;
        $this->url_segment = $url_segment;
    }

    /**
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }

    public function addGroup(RouteGroup $group)
    {
        foreach ($group->getRoutes() as $route) {
            $this->routes[] = $route;
        }
        $this->groups[] = $group;
    }

    public function updateRoutes()
    {
        foreach ($this->groups as $group) {
            $group->updateRoutes();
        }

        foreach ($this->getRoutes() as $route) {
            if ($this->url_segment !== false) {
                $route->updateUrl($this->url_segment);
            }
        }
    }

    /**
     * @return Route[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param Route $route
     * @return bool
     */
    public function includes(Route $route)
    {
        foreach ($this->routes as $match_route) {
            if ($route->getUrl() == $match_route->getUrl()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $url_segment
     */
    public function setUrlSegment($url_segment)
    {
        $this->url_segment = $url_segment;
    }

    /**
     * @return string
     */
    public function getUrlSegment()
    {
        return $this->url_segment;
    }
}
