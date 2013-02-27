<?php
use HtmlObject\HtmlObject;

class HtmlObjectTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $this->object = new HtmlObject('p', 'foo');
  }

  public function testCanCreateHtmlObject()
  {
    $this->assertEquals('<p>foo</p>', $this->object->render());
  }

  public function testCanDynamicallyCreateObjects()
  {
    $object = HtmlObject::p('foo')->class('bar');

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
    $object = HtmlObject::strong('foo');
    $this->object->nest('strong', 'foo');

    $this->assertEquals('<p>foo<strong>foo</strong></p>', $this->object->render());
  }

  public function testCanNestObjects()
  {
    $object = HtmlObject::strong('foo');
    $this->object->nest($object);

    $this->assertEquals('<p>foo<strong>foo</strong></p>', $this->object->render());
  }
}