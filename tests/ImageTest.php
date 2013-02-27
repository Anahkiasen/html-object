<?php
use HtmlObject\Image;

class ImageTest extends PHPUnit_Framework_TestCase
{
  public function testCanCreateList()
  {
    $image = Image::create('foo.jpg', 'foo');

    $this->assertEquals('<img src="foo.jpg" alt="foo">', $image->render());
  }
}