<?php
namespace HtmlObject;

/**
 * A list element (ul, ol, etc.).
 */
class Lists extends Element
{
    /**
     * Default element.
     *
     * @type string
     */
    protected $element = 'ul';

    /**
     * Default element for nested children.
     *
     * @type string
     */
    protected $defaultChild = 'li';
}
