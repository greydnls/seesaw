<?php namespace spec\Kayladnls\Seesaw;

use Kayladnls\Seesaw\Route;
use Kayladnls\Seesaw\RouteGroup;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RouteGroupSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(RouteGroup::class);
    }

    public function it_can_add_a_route(Route $route)
    {
        $route->beADoubleOf(Route::class);
        $this->addRoute($route);

        $this->getRoutes()->shouldContain($route);
    }

    public function it_can_tell_if_it_includes_a_route(Route $route)
    {
        $route->getAction()->willReturn('FooController::test');
        $route->getVerb()->willReturn('GET');
        $route->getUrl()->willReturn('123/345');
        $this->addRoute($route);

        $this->includes($route)->shouldReturn(true);
    }

    public function it_can_tell_if_it_doesnt_include_a_route(Route $route)
    {
        $route->getAction()->willReturn('FooController::test');
        $route->getVerb()->willReturn('GET');
        $route->getUrl()->willReturn('123/345');

        $this->includes($route)->shouldReturn(false);
    }

    public function it_can_get_and_set_url_segments()
    {
        $this->setUrlSegment('yolo');

        $this->getUrlSegment()->shouldReturn('yolo');
    }

    public function it_can_add_route_groups()
    {
        $group = new RouteGroup([Route::get('/yolo', 'Foo::bar'), Route::get('/fomo', 'Foo::bar')], 'tomorrow');

        $this->beConstructedWith([], 'today');

        $this->addGroup($group);

        $this->updateRoutes();

        $routes = $this->getRoutes();

        $routes->shouldBeArray();

        $routes[0]->getUrl()->shouldEqual('today/tomorrow/yolo');
    }
}
