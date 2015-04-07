<?php namespace Kayladnls\BackAlley;

use League\Route\RouteCollection;

class BackAlley extends RouteCollection
{
    protected $namedRoutes = [];
    protected $base_url;

    public static function __callStatic($method, $args)
    {
        if ($method == "route")
        {
            echo "YOLO";
        }
    }

    public function setBaseUrl($url)
    {
        $this->base_url = $url;
    }

    public function getBaseUrl()
    {
        return $this->base_url;
    }

    public function route($name)
    {
        if (isset($this->namedRoutes[$name])) {
            return $this->namedRoutes[$name];
        }

        if ($this->inferRoute($name) !== false) {
            return $this->inferRoute($name);
        }
    }

    /**
     * Add a named route to the collection
     */
    public function addNamedRoute($name, $method, $route, $handler, $strategy = null)
    {
        $this->namedRoutes[$name] = $route;
        parent::addRoute($method, $route, $handler, $strategy);
    }

    private function inferRoute($name)
    {
        $named_route = $this->parseCamel($name);

        $registered_routes = $this->getData()[0];

        if (isset($registered_routes[$named_route ]))
            return $named_route;
        else
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
}