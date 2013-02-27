<?php
use HtmlObject\Lists;

class ListsTest extends PHPUnit_Framework_TestCase
{
  public function testCanCreateList()
  {
    $list = new Lists('ul');

    $this->assertEquals('<ul></ul>', $list->render());
  }

  public function testCanCreateListWithChildren()
  {
    $list = Lists::ul(array(
      'foo', 'bar',
    ));

    $this->assertEquals('<ul><li>foo</li><li>bar</li></ul>', $list->render());
  }

  public function testCanSetCustomElementsOnChildren()
  {
    $list = Lists::ul(array(
      'a' => 'foo', 'bar',
    ));

    $this->assertEquals('<ul><a>foo</a><li>bar</li></ul>', $list->render());
  }
}