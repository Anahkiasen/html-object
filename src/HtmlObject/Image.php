<?php
namespace HtmlObject;

use HtmlObject\Traits\Tag;

/**
 * An image
 */
class Image extends Tag
{
  /**
   * Default element
   *
   * @var string
   */
  protected $defaultElement = 'img';

  /**
   * Whether the element is self closing
   *
   * @var boolean
   */
  protected $isSelfClosing = true;

  ////////////////////////////////////////////////////////////////////
  //////////////////////////// CORE METHODS //////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Create a new image tag
   *
   * @param string $src        Image source
   * @param string $alt        Image alt text
   * @param array  $attributes
   *
   * @return Image
   */
  public function __construct($src = '#', $alt = null, $attributes = array())
  {
    $attributes['src'] = $src;
    $attributes['alt'] = $alt;

    $this->setTag(null, null, $attributes);
  }

  /**
   * Static alias for constructor
   *
   * @param string $src        Image source
   * @param string $alt        Image alt text
   * @param array  $attributes
   *
   * @return Image
   */
  public static function create($src = '#', $alt = null, $attributes = array())
  {
    return new static($src, $alt, $attributes);
  }
}
