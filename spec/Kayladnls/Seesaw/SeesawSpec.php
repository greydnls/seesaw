<?php

namespace spec\Kayladnls\Seesaw;

use Kayladnls\Seesaw\Route;
use Kayladnls\Seesaw\RouteCollection;
use League\Container\Container;
use League\Container\ContainerInterface;
use League\Route\Dispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class SeesawSpec extends ObjectBehavior
{
    function let(ContainerInterface $container)
    {
        $this->beConstructedWith(null, null, $container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kayladnls\Seesaw\Seesaw');
    }

    function it_can_add_a_named_outputRoute()
    {
        $this->addNamedRoute('JimBob', 'GET', 'url/jim/bob', function(){});

        $this->outputRoute('JimBob')->__toString()->shouldReturn('/url/jim/bob');
    }

    public function it_will_throw_error_when_it_cannot_find_a_named_outputRoute()
    {
        $this->shouldThrow('\FastRoute\BadRouteException')->duringOutputRoute('something_something_blah_blah');
    }

    function it_can_add_a_parameterized_outputRoute()
    {
        $this->addNamedRoute('JimBob', 'GET', 'url/jim/bob/{id}', function(){});

        $this->outputRoute('JimBob', [123])->__toString()->shouldReturn('/url/jim/bob/123');
    }

    function it_can_be_accessed_statically()
    {
        $this->addNamedRoute('JimBob', 'GET', 'url/jim/bob', function(){});

        $this::outputRoute('JimBob')->__toString()->shouldReturn('/url/jim/bob');
    }

    function it_can_set_a_base_url()
    {
        $this->beConstructedWith(new RouteCollection(), 'http://yolo.com', new Container());
        $this->setBaseUrl('http://yolo.com');

        $this->getBaseUrl()->shouldReturn('http://yolo.com');
    }

    function it_can_infer_a_route_name()
    {
        $this->add(Route::get('/add/new/route', function(){}));

        $this->outputRoute('AddNewRoute')->__toString()->shouldReturn('/add/new/route');
    }

    function it_can_add_an_unnamed_outputRoute()
    {
        $route = Route::get('/one/two/three',
            function($request, Response $response)
            {
                $response->setContent('YOLO');
                return $response;
            });

        $this->add($route);

        $this->routeIsRegistered($route)->shouldReturn(true);
    }
}
