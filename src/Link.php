<?php

namespace HtmlObject;

use HtmlObject\Traits\Tag;

/**
 * A basic link.
 */
class Link extends Tag
{
    /**
     * An UrlGenerator instance to use.
     *
     * @var UrlGenerator
     */
    public static $urlGenerator;

    /**
     * The default element.
     *
     * @var string
     */
    protected $element = 'a';

    ////////////////////////////////////////////////////////////////////
    /////////////////////////// CORE METHODS ///////////////////////////
    ////////////////////////////////////////////////////////////////////

    /**
     * Create a new Link.
     *
     * @param string      $link       The link href
     * @param string|null $value      The link's text
     * @param array       $attributes
     */
    public function __construct($link = '#', $value = null, $attributes = array())
    {
        if (static::$urlGenerator) {
            $link = static::$urlGenerator->to($link);
        }
        if (is_null($value)) {
            $value = $link;
        }

        $attributes['href'] = $link;

        $this->setTag('a', $value, $attributes);
    }

    /**
     * Static alias for constructor.
     *
     * @param string      $link       The link href
     * @param string|null $value      The link's text
     * @param array       $attributes
     *
     * @return $this
     */
    public static function create($link = '#', $value = null, $attributes = array())
    {
        return new static($link, $value, $attributes);
    }

    ////////////////////////////////////////////////////////////////////
    ////////////////////////// PUBLIC METHODS //////////////////////////
    ////////////////////////////////////////////////////////////////////

    /**
     * Make the link blank.
     *
     * @return $this
     */
    public function blank()
    {
        $this->setAttribute('target', '_blank');

        return $this;
    }
}
