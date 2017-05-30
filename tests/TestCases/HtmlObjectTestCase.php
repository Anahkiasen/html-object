<?php

namespace HtmlObject\TestCases;

use HtmlObject\Traits\Tag;
use PHPUnit\Framework\DOMTestCase;

class HtmlObjectTestCase extends DOMTestCase
{
    /**
     * Reset some attributes on each test.
     */
    public function setUp()
    {
        Tag::$config['doctype'] = 'html';
    }

    ////////////////////////////////////////////////////////////////////
    ///////////////////////////// MATCHERS /////////////////////////////
    ////////////////////////////////////////////////////////////////////

    /**
     * Create a basic matcher for a tag.
     *
     * @param string $tag
     * @param string $content
     * @param array  $attributes
     *
     * @return array
     */
    protected function getMatcher($tag = 'p', $content = 'foo', $attributes = array())
    {
        $tag = array('tag' => $tag);

        if ($content) {
            $tag['content'] = $content;
        }

        if (!empty($attributes)) {
            $tag['attributes'] = $attributes;
        }

        return $tag;
    }

    /**
     * Create a matcher for an input field.
     *
     * @param string $type
     * @param string $name
     * @param string $value
     * @param array  $attributes
     *
     * @return array
     */
    protected function getInputMatcher($type, $name, $value = null, $attributes = array())
    {
        $input = $this->getMatcher('input', null, array(
            'name' => $name,
            'value' => $value,
            'type' => $type,
        ));

        return $input;
    }

    ////////////////////////////////////////////////////////////////////
    ///////////////////////////// HELPERS //////////////////////////////
    ////////////////////////////////////////////////////////////////////

    /**
     * Enhanced version of assertTag.
     *
     * @param array  $matcher The tag matcher
     * @param string $html    The HTML
     */
    protected function assertHTML($matcher, $html)
    {
        $html = $html instanceof Tag ? $html->__toString() : $html;

        $selector = $matcher['tag'];
        if (isset($matcher['attributes'])) {
            foreach ($matcher['attributes'] as $key => $value) {
                $selector .= sprintf('[%s=\'%s\']', $key, $value);
            }
        }

        $this->assertSelectCount($selector, true, $html);
        if (isset($matcher['content'])) {
            $this->assertSelectEquals($selector, $matcher['content'], true, $html);
        }
    }
}
