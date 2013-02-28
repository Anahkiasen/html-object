<?php
/**
 * Text
 *
 * A TextNode
 */
namespace HtmlObject;

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