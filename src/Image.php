<?php

namespace HtmlObject;

use HtmlObject\Traits\Tag;

/**
 * An image.
 */
class Image extends Tag
{
    /**
     * An UrlGenerator instance to use.
     *
     * @var UrlGenerator
     */
    public static $urlGenerator;

    /**
     * The Image's tag.
     *
     * @var string
     */
    protected $element = 'img';

    /**
     * Whether the element is self closing.
     *
     * @var bool
     */
    protected $isSelfClosing = true;

    ////////////////////////////////////////////////////////////////////
    //////////////////////////// CORE METHODS //////////////////////////
    ////////////////////////////////////////////////////////////////////

    /**
     * Create a new image tag.
     *
     * @param string      $src        Image source
     * @param string|null $alt        Image alt text
     * @param array       $attributes
     */
    public function __construct($src = '#', $alt = null, $attributes = array())
    {
        if (static::$urlGenerator) {
            $src = static::$urlGenerator->asset($src);
        }
        if (!$alt) {
            $alt = basename($src);
        }

        $attributes['src'] = $src;
        $attributes['alt'] = $alt;

        $this->attributes = $attributes;
    }

    /**
     * Static alias for constructor.
     *
     * @param string      $src        Image source
     * @param string|null $alt        Image alt text
     * @param array       $attributes
     *
     * @return $this
     */
    public static function create($src = '#', $alt = null, $attributes = array())
    {
        return new static($src, $alt, $attributes);
    }
}
