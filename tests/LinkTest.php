<?php
use HtmlObject\Link;

class LinkTest extends PHPUnit_Framework_TestCase
{
  public function testCanCreateList()
  {
    $link = Link::create('#foo', 'bar');

    $this->assertEquals('<a href="#foo">bar</a>', $link->render());
  }

  public function testCanMakeLinkBlank()
  {
    $link = Link::create('#foo', 'bar')->blank();

    $this->assertEquals('<a href="#foo" target="_blank">bar</a>', $link->render());
  }
}