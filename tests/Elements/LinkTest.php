<?php
namespace HtmlObject\Elements;

use HtmlObject\Link;
use HtmlObject\TestCases\HtmlObjectTestCase;

class LinkTest extends HtmlObjectTestCase
{
    public function testCanCreateList()
    {
        $link    = Link::create('#foo', 'bar');
        $matcher = $this->getMatcher('a', 'bar', array('href' => '#foo'));

        $this->assertHTML($matcher, $link);
    }

    public function testCanMakeLinkBlank()
    {
        $link    = Link::create('#foo', 'bar')->blank();
        $matcher = $this->getMatcher('a', 'bar', array('target' => '_blank', 'href' => '#foo'));

        $this->assertHTML($matcher, $link);
    }
}
