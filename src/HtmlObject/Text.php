<?php
namespace HtmlObject;

/**
 * A TextNode
 */
class Text extends Element
{
  /**
   * Create a TextNode
   *
   * @param string $value
   */
  public function __construct($value = null)
  {
    $this->value = $value;
  }

  /**
   * Static alias for constructor
   *
   * @param string $element    An element name
   * @param string $value      The tag value
   * @param array  $attributes
   *
   * @return Text
   */
  public static function create($element = null, $value = null, $attributes = array())
  {
    return new static($element);
  }

  /**
   * Render a TextNode
   *
   * @return string
   */
  public function render()
  {
    return $this->value;
  }
}