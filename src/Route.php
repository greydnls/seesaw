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

    /**
     * @var string
     */
    protected $verb;

    /**
     * @var string
     */
    protected $url_string;


    /**
     * @var string
     */
    protected $action;


    /**
     * @var array
     */
    private $acceptable_verbs = ['POST', 'PUT', 'DELETE', 'GET'];


    private function __construct($verb, $url, $action, $base_url = null, $parameters = array())
    {
        $this->validateVerb($verb);
        $this->action = $action;
        $this->url_string = $url;
        $this->base_url = $base_url;
        $this->parameters = $parameters;

    }

    public static function reverse($route, $base_url = null, $parameters = array())
    {
        return new static("GET", $route, null, $base_url, $parameters);
    }

    function __toString()
    {
        if ($this->force_ssl == true) {
            $this->base_url = str_replace('http://', 'https://', $this->base_url);
        }

        $this->base_url = trim($this->base_url, '/');
        $this->url_string = trim($this->url_string, '/');

        $this->compileParameters();

        if ($this->is_relative == true) {
            $return_url = "/" . $this->url_string;
        }
        else {
            $return_url = $this->base_url . "/" . $this->url_string;
        }

        return $return_url;
    }

    private function compileParameters()
    {
        $pieces = explode('/', $this->url_string);
        foreach ($pieces as $k => $piece)
        {
            preg_match("/{.*}/", $piece, $matches);

            if (!empty($matches))
            {
                $pieces[$k] = array_shift($this->parameters);
            }
        }
        $this->url_string = implode('/', $pieces);
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

    /**
     * @param $verb
     */
    private function validateVerb($verb)
    {
        if (!in_array($verb, $this->acceptable_verbs)) {
            throw new \InvalidArgumentException('Invalid Route Verb Supplied.');
        }

        $this->verb = $verb;
    }

    /**
     * @param $url
     * @param $action
     * @return static
     */
    public static function get($url, $action, $base_url = null, $parameters = array())
    {
        return new static('GET', $url, $action, $base_url, $parameters);
    }

    /**
     * @param string $url
     * @param string $action
     * @return static
     */
    public static function post($url, $action, $base_url = null, $parameters = array())
    {
        return new static('POST', $url, $action, $base_url, $parameters);
    }

    /**
     * @param string $url
     * @param string $action
     * @return static
     */
    public static function delete($url, $action, $base_url = null, $parameters = array())
    {
        return new static('DELETE', $url, $action, $base_url, $parameters);
    }

    /**
     * @param string $url
     * @param string $action
     * @return static
     */
    public static function put($url, $action, $base_url = null, $parameters = array())
    {
        return new static('PUT', $url, $action, $base_url, $parameters);
    }

    /**
     * @return string
     */
    public function getVerb()
    {
        return $this->verb;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url_string;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    public function updateUrl($segment)
    {
        $segment = trim($segment, "/");
        $this->url_string = $segment."/". $this->url_string;

        $this->url_string = str_replace('//', '/', $this->url_string);
    }


}


