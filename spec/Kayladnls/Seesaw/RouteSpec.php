<?php namespace spec\Kayladnls\Seesaw;

use Kayladnls\Seesaw\Route;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RouteSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough('get', ['alias', 'something']);
    }

    public function it_can_create_a_get_route()
    {
        $this->beConstructedThrough('get', ['something', 'something']);
        $this->shouldHaveType(Route::class);
        $this->getVerb()->shouldReturn('GET');
    }

    public function it_can_create_a_post_route()
    {
        $this->beConstructedThrough('post', ['something', 'something']);
        $this->shouldHaveType(Route::class);
        $this->getVerb()->shouldReturn('POST');
    }

    public function it_can_create_a_put_route()
    {
        $this->beConstructedThrough('put', ['something', 'something']);
        $this->shouldHaveType(Route::class);
        $this->getVerb()->shouldReturn('PUT');
    }

    public function it_can_create_a_delete_route()
    {
        $this->beConstructedThrough('delete', ['something', 'something']);
        $this->shouldHaveType(Route::class);
        $this->getVerb()->shouldReturn('DELETE');
    }

    public function it_can_get_url()
    {
        $this->beConstructedThrough('get', ['alias', 'something']);

        $this->getUrl()->shouldReturn('alias');
    }

    public function it_can_get_action()
    {
        $this->beConstructedThrough('get', ['alias', 'action']);

        $this->getAction()->shouldReturn('action');
    }

    public function it_cannot_use_invalid_verbs()
    {
        $this->shouldThrow('\Exception')->during('__construct', ['YOLO', 'something', 'something']);
    }

    public function it_can_update_a_url()
    {
        $this->beConstructedThrough('get', ['alias', 'action']);
        $this->updateUrl('/yolo/fomo');

        $this->getUrl()->shouldReturn('yolo/fomo/alias');
    }

    public function it_can_be_echoed()
    {
        $this->beConstructedThrough('get', ['/yolo/friend/blah', null]);
        $this->__toString()->shouldReturn('/yolo/friend/blah');
    }

    public function it_can_have_a_base_url()
    {
        $this->beConstructedThrough('get', ['/yolo/friend/blah', 'something::go', 'http://google.com']);
        $this->__toString()->shouldReturn('http://google.com/yolo/friend/blah');
    }

    public function it_can_echo_secure()
    {
        $this->beConstructedThrough('get', ['/yolo/friend/blah', 'something::go',  'http://google.com']);
        $this->secure()->__toString()->shouldReturn('https://google.com/yolo/friend/blah');
    }

    public function it_cannot_echo_secure_without_a_base_rule()
    {
        $this->beConstructedThrough('get', ['/yolo/friend/blah', 'something::blah']);
        $this->shouldThrow('Exception')->duringSecure();
    }

    public function it_can_echo_relative()
    {
        $this->beConstructedThrough('get', ['/yolo/friend/blah', 'http://google.com']);
        $this->relative()->__toString()->shouldReturn('/yolo/friend/blah');
    }
}
