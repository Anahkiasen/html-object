<?php
use HtmlObject\Element;

class TreeObjectTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $this->object = new Element('p', 'foo');
  }

  public function testCanNest()
  {
    $object = Element::strong('foo');
    $this->object->nest('strong', 'foo');

    $this->assertEquals('<p>foo<strong>foo</strong></p>', $this->object->render());
  }

  public function testCanNestStrings()
  {
    $object = Element::strong('foo');
    $this->object->nest('<strong>foo</strong>');

    $this->assertEquals('<p>foo<strong>foo</strong></p>', $this->object->render());
  }

  public function testCanNestObjects()
  {
    $object = Element::strong('foo');
    $this->object->nest($object);

    $this->assertEquals('<p>foo<strong>foo</strong></p>', $this->object->render());
  }

  public function testCanGetNestedElements()
  {
    $object = Element::strong('foo');
    $this->object->nest($object, 'foo');

    $this->assertEquals($object, $this->object->getChild('foo'));
  }

  public function testCanNestMultipleValues()
  {
    $object = Element::strong('foo');
    $this->object->nestChildren(array('strong' => 'foo', 'em' => 'bar'));

    $this->assertEquals('<p>foo<strong>foo</strong><em>bar</em></p>', $this->object->render());
  }

  public function testCanNestMultipleElements()
  {
    $foo = Element::strong('foo');
    $bar = Element::p('bar');
    $this->object->nestChildren(array(
      'foo' => $foo,
      'bar' => $bar,
    ));

    $this->assertEquals($foo, $this->object->getChild('foo'));
    $this->assertEquals($bar, $this->object->getChild('bar'));
  }

  public function testCanNestMultipleObjects()
  {
    $strong = Element::strong('foo');
    $em = Element::em('bar');
    $this->object->nestChildren(array($strong, $em));

    $this->assertEquals('<p>foo<strong>foo</strong><em>bar</em></p>', $this->object->render());
  }

  public function testCanWalkTree()
  {
    $strong = Element::strong('foo');
    $this->object->nest($strong);

    $this->assertEquals($this->object, $this->object->getChild(0)->getParent());
  }

  public function testCanModifyChildren()
  {
    $strong = Element::strong('foo');
    $this->object->nest($strong);
    $this->object->getChild(0)->addClass('foo');

    $this->assertEquals('<p>foo<strong class="foo">foo</strong></p>', $this->object->render());
  }

  public function testCanCrawlToTextNode()
  {
    $this->object->nest('<strong>foo</strong>');
    $this->object->getChild(0)->addClass('foo');

    $this->assertEquals('<p>foo<strong>foo</strong></p>', $this->object->render());
  }
}