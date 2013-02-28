<?php
namespace HtmlObject;

/**
 * A basic link
 */
class Link extends Element
{
  /**
   * Default element
   *
   * @var string
   */
  protected $defaultElement = 'a';

  /**
   * Static alias for constructor
   *
   * @param string $link       The link href
   * @param string $value      The link's text
   * @param array  $attributes
   *
   * @return Link
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
