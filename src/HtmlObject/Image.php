<?php
/**
 * Image
 *
 * An <img>
 */
namespace HtmlObject;

class Image extends Element
{
  /**
   * Default element
   * @var string
   */
  protected $defaultElement = 'img';

  /**
   * Whether the element is self closing
   * @var boolean
   */
  protected $selfClosing = true;

  /**
   * Static alias for constructor
   */
  public static function create($src = '#', $alt = null, $attributes = array())
  {
    $attributes['src'] = $src;
    $attributes['alt'] = $alt;

    return new static(null, null, $attributes);
  }
}