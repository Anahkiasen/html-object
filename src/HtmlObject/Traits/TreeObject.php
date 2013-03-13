<?php
namespace HtmlObject\Traits;

use HtmlObject\Element;
use HtmlObject\Text;

/**
 * An abstract class to create and manage trees of objects
 */
abstract class TreeObject
{
  /**
   * Parent of the object
   *
   * @var TreeObject
   */
  protected $parent;

  /**
   * The name of the child for the parent
   *
   * @var string
   */
  protected $parentIndex;

  /**
   * Children of the object
   *
   * @var array
   */
  protected $children = array();

  // Defaults ------------------------------------------------------ /

  /**
   * Default element for nested children
   *
   * @var string
   */
  protected $defaultChild;

  ////////////////////////////////////////////////////////////////////
  /////////////////////////////// PARENT /////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Get the Element's parent
   *
   * @param integer $levels The number of levels to go back up
   *
   * @return Element
   */
  public function getParent($levels = null)
  {
    if (!$levels) return $this->parent;

    $subject = $this;
    for ($i = 0; $i <= $levels; $i++) {
      $subject = $subject->getParent();
    }

    return $subject;
  }

  /**
   * Set the parent of the element
   *
   * @param TreeObject $parent
   *
   * @return TreeObject
   */
  public function setParent(TreeObject $parent)
  {
    $this->parent = $parent;

    return $this;
  }

  /**
   * Check if an object has a parent
   *
   * @return boolean
   */
  public function hasParent()
  {
    return (bool) $this->parent;
  }

  ////////////////////////////////////////////////////////////////////
  ////////////////////////////// CHILDREN ////////////////////////////
  ////////////////////////////////////////////////////////////////////

  // Get ----------------------------------------------------------- /

  /**
   * Get a specific child of the element
   *
   * @param string $name The Element's name
   *
   * @return Element
   */
  public function getChild($name)
  {
    // Direct fetching
    $name = explode('.', $name);
    if (sizeof($name) == 1) {
      return Helpers::arrayGet($this->getChildren(), $name[0]);
    }

    // Recursive fetching
    $subject = $this;
    foreach ($name as $child) {
      $subject = $subject->getChild($child);
    }

    return $subject;
  }

  /**
   * Get all children
   *
   * @return array
   */
  public function getChildren()
  {
    return $this->children;
  }

  /**
   * Check if the object has children
   *
   * @return boolean
   */
  public function hasChildren()
  {
    return !is_null($this->children) and !empty($this->children);
  }

  // Set ----------------------------------------------------------- /

  /**
   * Nests an object withing the current object
   *
   * @param Tag|string $element    An element name or an Tag
   * @param string         $value      The Tag's alias or the element's content
   * @param array          $attributes
   *
   * @return Tag
   */
  public function nest($element, $value = null, $attributes = array())
  {
    // Alias for nestChildren
    if (is_array($element)) {
      return $this->nestChildren($element);
    }

    // Tag nesting
    if ($element instanceof Tag) {
      return $this->setChild($element, $value);
    }

    // Shortcuts and strings
    if (strpos($element, '<') === false) {
      $element = new Element($element, $value, $attributes);
    } else {
      $element = new Text($element);
    }

    $this->setChild($element);

    return $this;
  }

  /**
   * Nest an array of objects/values
   *
   * @param array $children
   */
  public function nestChildren($children)
  {
    if (!is_array($children)) return $this;

    foreach ($children as $element => $value) {
      if (is_numeric($element)) {
        if($value instanceof TreeObject) $this->setChild($value);
        elseif($this->defaultChild) $this->nest($this->defaultChild, $value);
      } else {
        if($value instanceof TreeObject) $this->setChild($value, $element);
        else $this->nest($element, $value);
      }
    }

    return $this;
  }

  /**
   * Add an object to the current object
   *
   * @param string|TreeObject  $child The child
   * @param string             $name  Its name
   *
   * @return TreeObject
   */
  public function setChild($child, $name = null)
  {
    if (!$name) $name = sizeof($this->children);

    // Get subject of the setChild
    $subject = explode('.', $name);
    $name = array_pop($subject);
    $subject = implode('.', $subject);
    $subject = $subject ? $this->getChild($subject) : $this;

    // Bind parent to child
    if ($child instanceof TreeObject) {
      $child->setParent($subject);
    }

    // Add object to children
    $subject->children[$name] = $child;

    return $this;
  }
}
