<?php
use HtmlObject\Input;

class InputTest extends HtmlObjectTests
{
  public function testCanCreateBasicInput()
  {
    $input = new Input('text', 'foo', 'bar');
    $matcher = $this->getInputMatcher('text', 'foo', 'bar');

    $this->assertHTML($matcher, $input);
  }

  public function testCanDynamicallyCreateInputTypes()
  {
    $input = Input::text('foo', 'bar');
    $matcher = $this->getInputMatcher('text', 'foo', 'bar');

    $this->assertHTML($matcher, $input);
  }
}