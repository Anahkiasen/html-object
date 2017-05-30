<?php

namespace HtmlObject;

use HtmlObject\Traits\Tag;

/**
 * A TextNode.
 */
class Text extends Tag
{
    /**
     * Create a TextNode.
     *
     * @param string|null $value
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * Static alias for constructor.
     *
     * @param string|null $value The text value
     *
     * @return $this
     */
    public static function create($value = null)
    {
        return new static($value);
    }

    /**
     * Render a TextNode.
     *
     * @return string|null
     */
    public function render()
    {
        return $this->value;
    }
}
