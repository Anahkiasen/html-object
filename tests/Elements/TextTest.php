<?php
namespace HtmlObject\Elements;

use HtmlObject\TestCases\HtmlObjectTestCase;
use HtmlObject\Text;

class TextTest extends HtmlObjectTestCase
{
    public function testCanCreateTextNodes()
    {
        $text = new Text('foo');

        $this->assertEquals('foo', Text::create('foo')->render());
        $this->assertEquals('foo', $text->render());
    }
}
