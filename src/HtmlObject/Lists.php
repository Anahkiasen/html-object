<?php
/**
 * List
 *
 * A list element (ul, ol, etc.)
 */
namespace HtmlObject;

class Lists extends Element
{
  /**
   * Default element
   * @var string
   */
  protected $defaultElement = 'ul';

  /**
   * Default element for nested children
   * @var string
   */
  protected $defaultChild = 'li';

  /**
   * As lists don't have a value, they're its children
   *
   * @param array $value
   */
  public function setValue($value)
  {
    $this->nestChildren($value);

    return $this;
  }
}
