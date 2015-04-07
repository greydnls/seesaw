<?php
/**
 * Created by PhpStorm.
 * User: kayladaniels
 * Date: 4/7/15
 * Time: 12:05 AM
 */

namespace Kayladnls\Seesaw;


class Route
{
    protected $force_ssl = false;
    protected $is_relative = false;
    protected $base_url;
    protected $url_string;

    private function __construct($route, $base_url = null)
    {
        $this->url_string = $route;
        $this->base_url = $base_url;
    }

    public static function create($route, $base_url = null)
    {
        return new static($route, $base_url);
    }

    function __toString()
    {
        if ($this->force_ssl == true) {
            $this->base_url = str_replace('http://', 'https://', $this->base_url);
        }

        $this->base_url = trim($this->base_url, '/');
        $this->url_string = trim($this->url_string, '/');

        if ($this->is_relative == true) {
            $return_url = "/" . $this->url_string;
        }
        else {
            $return_url = $this->base_url . "/" . $this->url_string;
        }

        return $return_url;
    }

    public function secure()
    {
        if ($this->base_url == null) {
            throw new \Exception('Cannot generate a secure url without a base url');
        }

        $this->force_ssl = true;

        return $this;
    }

    public function relative()
    {
        $this->is_relative = true;

        return $this;
    }


}