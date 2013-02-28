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

  public function testCanGetAttributes()
  {
    $this->object->setAttribute('data-foo', 'bar');

    $this->assertEquals(array('data-foo' => 'bar'), $this->object->getAttributes());
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

  public function testCanGetValue()
  {
    $this->assertEquals('foo', $this->object->getValue());
  }

  public function testSimilarClassesStillGetAdded()
  {
    $this->object->addClass('alert-success');
    $this->object->addClass('alert');

    $this->assertEquals('<p class="alert-success alert">foo</p>', $this->object->render());
  }

  public function testCanManuallyOpenElement()
  {
    $element = $this->object->open().'foobar'.$this->object->close();

    $this->assertEquals('<p>foobar</p>', $element);
  }

  public function testCanWrapValue()
  {
    $this->object->wrapValue('strong');

    $this->assertEquals('<p><strong>foo</strong></p>', $this->object->render());
  }
}