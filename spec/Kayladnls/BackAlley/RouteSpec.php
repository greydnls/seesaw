<?php

namespace spec\Kayladnls\BackAlley;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RouteSpec extends ObjectBehavior
{
    public function it_can_be_echoed()
    {
        $this->beConstructedThrough('create', ['/yolo/friend/blah', null]);
        $this->__toString()->shouldReturn('/yolo/friend/blah');
    }

    public function it_can_have_a_base_url()
    {
        $this->beConstructedThrough('create', ['/yolo/friend/blah', 'http://google.com']);
        $this->__toString()->shouldReturn('http://google.com/yolo/friend/blah');
    }

    public function it_can_echo_secure()
    {
        $this->beConstructedThrough('create', ['/yolo/friend/blah', 'http://google.com']);
        $this->secure()->__toString()->shouldReturn('https://google.com/yolo/friend/blah');
    }

    public function it_cannot_echo_secure_without_a_base_rule()
    {
        $this->beConstructedThrough('create', ['/yolo/friend/blah']);
        $this->shouldThrow('Exception')->duringSecure();
    }

    public function it_can_echo_relative()
    {
        $this->beConstructedThrough('create', ['/yolo/friend/blah', 'http://google.com']);
        $this->relative()->__toString()->shouldReturn('/yolo/friend/blah');
    }
}
