<?php
use HtmlObject\Element;

class ElementTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $this->object = new Element('p', 'foo');
  }

  public function testCanCreateHtmlObject()
  {
    $this->assertEquals('<p>foo</p>', $this->object->render());
  }

  public function testCanCreateDefaultElement()
  {
    $this->assertEquals('<p>foo</p>', Element::create()->setValue('foo'));
  }

  public function testCanDynamicallyCreateObjects()
  {
    $object = Element::p('foo')->class('bar');

    $this->assertEquals('<p class="bar">foo</p>', $object->render());
  }

  public function testCanSetAnAttribute()
  {
    $this->object->setAttribute('data-foo', 'bar');

    $this->assertEquals('<p data-foo="bar">foo</p>', $this->object->render());
  }

  public function testCanDynamicallySetAttributes()
  {
    $this->object->data_foo('bar');

    $this->assertEquals('<p data-foo="bar">foo</p>', $this->object->render());
  }

  public function testCanReplaceAttributes()
  {
    $this->object->setAttribute('data-foo', 'bar');
    $this->object->replaceAttributes(array('foo' => 'bar'));

    $this->assertEquals('<p foo="bar">foo</p>', $this->object->render());
  }

  public function testCanMergeAttributes()
  {
    $this->object->setAttribute('data-foo', 'bar');
    $this->object->setAttributes(array('foo' => 'bar'));

    $this->assertEquals('<p data-foo="bar" foo="bar">foo</p>', $this->object->render());
  }

  public function testCanAppendClass()
  {
    $this->object->setAttribute('class', 'foo');
    $this->object->addClass('foo');
    $this->object->addClass('bar');

    $this->assertEquals('<p class="foo bar">foo</p>', $this->object->render());
  }

  public function testCanFetchAttributes()
  {
    $this->object->foo('bar');

    $this->assertEquals('bar', $this->object->foo);
  }

  public function testCanChangeElement()
  {
    $this->object->setElement('strong');

    $this->assertEquals('<strong>foo</strong>', $this->object->render());
  }

  public function testCanChangeValue()
  {
    $this->object->setValue('bar');

    $this->assertEquals('<p>bar</p>', $this->object->render());
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
    $this->object->nestElement($object);

    $this->assertEquals('<p>foo<strong>foo</strong></p>', $this->object->render());
  }

  public function testCanGetNestedElements()
  {
    $object = Element::strong('foo');
    $this->object->nestElement($object, 'foo');

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

  public function testSimilarClassesStillGetAdded()
  {
    $alert = Element::p();
    $alert->addClass('alert-success');
    $alert->addClass('alert');

    $this->assertEquals('<p class="alert-success alert"></p>', $alert->render());
  }

  public function testCanManuallyOpenElement()
  {
    $element = $this->object->open().'foobar'.$this->object->close();

    $this->assertEquals('<p>foobar</p>', $element);
  }
}