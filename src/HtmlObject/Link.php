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
   * Create a new Link
   *
   * @param string $link       The link href
   * @param string $value      The link's text
   * @param array  $attributes
   *
   * @return Link
   */
  public function __construct($link = '#', $value = null, $attributes = array())
  {
    if (!$value) $value = $link;
    $attributes['href'] = $link;

    $this->value = $value;
    $this->setElement(null);
    $this->replaceAttributes($attributes);
  }

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
    return new static($link, $value, $attributes);
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
