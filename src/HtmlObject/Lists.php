<?php
namespace HtmlObject;

/**
 * A list element (ul, ol, etc.)
 */
class Lists extends Element
{
  /**
   * Default element
   *
   * @var string
   */
  protected $defaultElement = 'ul';

  /**
   * Default element for nested children
   *
   * @var string
   */
  protected $defaultChild = 'li';

  ////////////////////////////////////////////////////////////////////
  ////////////////////////// PUBLIC METHODS //////////////////////////
  ////////////////////////////////////////////////////////////////////

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
