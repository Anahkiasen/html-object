<?php
use HtmlObject\HtmlObject;

class HtmlObjectTest extends PHPUnit_Framework_TestCase
{
  public function testCanCreateHtmlObject()
  {
    $object = new HtmlObject('p', 'foo');

    $this->assertEquals('<p>foo</p>', $object->render());
  }
}