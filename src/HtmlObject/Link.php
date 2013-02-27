<?php
/**
 * Link
 *
 * An <a>
 */
namespace HtmlObject;

class Link extends Element
{
  /**
   * Default element
   * @var string
   */
  protected $defaultElement = 'a';

  /**
   * Static alias for constructor
   */
  public static function create($link = '#', $value = null, $attributes = array())
  {
    if (!$value) $value = $link;
    $attributes['href'] = $link;

    return new static(null, $value, $attributes);
  }

  /**
   * Make the link blank
   */
  public function blank()
  {
    $this->setAttribute('target', '_blank');

    return $this;
  }
}