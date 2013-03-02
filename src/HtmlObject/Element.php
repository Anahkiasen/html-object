<?php
namespace HtmlObject;

use HtmlObject\Traits\TreeObject;
use HtmlObject\Traits\Helpers;

/**
 * An abstraction of an HTML element
 */
class Element extends TreeObject
{
  /**
   * The element name
   * @var string
   */
  protected $element;

  /**
   * The object's value
   * @var mixed
   */
  protected $value;

  /**
   * The object's attribute
   * @var array
   */
  protected $attributes = array();

  /**
   * Whether the element is self closing
   * @var boolean
   */
  protected $selfClosing = false;

  // Defaults ------------------------------------------------------ /

  /**
   * Default element
   * @var string
   */
  protected $defaultElement = 'p';

  /**
   * Default element for nested children
   * @var string
   */
  protected $defaultChild;

  ////////////////////////////////////////////////////////////////////
  //////////////////////////// CORE METHODS //////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Creates a basic Element
   *
   * @param string $element
   * @param string $value
   * @param array  $attributes
   *
   * @return Element
   */
  public function __construct($element = null, $value = null, $attributes = array())
  {
    $this->setElement($element);
    $this->setValue($value);
    $this->attributes = $attributes;
  }

  /**
   * Static alias for constructor
   *
   * @param string $element
   * @param string $value
   * @param array  $attributes
   * @return Element
   */
  public static function create($element = null, $value = null, $attributes = array())
  {
    return new static($element, $value, $attributes);
  }

  /**
   * Render on string type
   *
   * @return string
   */
  public function __toString()
  {
    return $this->render();
  }

  ////////////////////////////////////////////////////////////////////
  ///////////////////////// ELEMENT RENDERING ////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Opens the Element
   *
   * @return string
   */
  public function open()
  {
    return '<'.$this->element.Helpers::parseAttributes($this->attributes).'>';
  }

  /**
   * Returns the Element's content
   *
   * @return string
   */
  public function getContent()
  {
    return $this->value.$this->renderChildren();
  }

  /**
   * Close the Element
   *
   * @return string
   */
  public function close()
  {
    return '</'.$this->element.'>';
  }

  /**
   * Default rendering method
   *
   * @return string
   */
  public function render()
  {
    if ($this->selfClosing) return $this->open();

    return $this->open().$this->getContent().$this->close();
  }

  ////////////////////////////////////////////////////////////////////
  /////////////////////////// MAGIC METHODS //////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Dynamically create an element
   *
   * @param string $method     The element
   * @param array  $parameters Value and attributes
   *
   * @return Element
   */
  public static function __callStatic($method, $parameters)
  {
    $value      = Helpers::arrayGet($parameters, 0);
    $attributes = Helpers::arrayGet($parameters, 1);

    return new static($method, $value, $attributes);
  }

  /**
   * Dynamically set attributes
   *
   * @param  string $method     An attribute
   * @param  array  $parameters Its value(s)
   */
  public function __call($method, $parameters)
  {
    // Replace underscores
    $method = str_replace('_', '-', $method);

    // Get value and set it
    $value = Helpers::arrayGet($parameters, 0, 'true');
    $this->$method = $value;

    return $this;
  }

  /**
   * Dynamically set an attribute
   *
   * @param string $attribute The attribute
   * @param string $value     Its value
   */
  public function __set($attribute, $value)
  {
    $this->attributes[$attribute] = $value;

    return $this;
  }

  /**
   * Get an attribute
   *
   * @param  string $attribute The desired attribute
   *
   * @return string            Its value
   */
  public function __get($attribute)
  {
    return Helpers::arrayGet($this->attributes, $attribute);
  }

  ////////////////////////////////////////////////////////////////////
  ////////////////////////////// ELEMENT /////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Change the object's element
   *
   * @param string $element
   */
  public function setElement($element)
  {
    $this->element = $element ?: $this->defaultElement;

    return $this;
  }

  /**
   * Change the object's value
   *
   * @param string $value
   */
  public function setValue($value)
  {
    $this->value = $value;

    return $this;
  }

  /**
   * Wrap the value in a tag
   *
   * @param string $tag The tag
   */
  public function wrapValue($tag)
  {
    $this->value = Element::create($tag, $this->value);

    return $this;
  }

  /**
   * Get the value
   *
   * @return string
   */
  public function getValue()
  {
    return $this->value;
  }

  ////////////////////////////////////////////////////////////////////
  ////////////////////////////// CHILDREN ////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Get all the children as a string
   *
   * @return string
   */
  public function renderChildren()
  {
    $children = $this->children;
    foreach ($children as $key => $child) {
      if ($child instanceof Element) {
        $children[$key] = $child->render();
      }
    }

    return implode($children);
  }

  /**
   * Nests an object withing the current object
   *
   * @param Element|string $element    An element name or an Element
   * @param string         $value      The Element's alias or the element's content
   * @param array          $attributes
   *
   * @return Element
   */
  public function nest($element, $value = null, $attributes = array())
  {
    // Element nesting
    if ($element instanceof Element) {
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
        if(is_object($value)) $this->setChild($value);
        elseif($this->defaultChild) $this->nest($this->defaultChild, $value);
      } else {
        if(is_object($value)) $this->setChild($value, $element);
        else $this->nest($element, $value);
      }
    }

    return $this;
  }

  ////////////////////////////////////////////////////////////////////
  //////////////////////////// ATTRIBUTES ////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Set an attribute
   *
   * @param string $attribute An attribute
   * @param string $value     Its value
   */
  public function setAttribute($attribute, $value = null)
  {
    $this->attributes[$attribute] = $value;

    return $this;
  }

  /**
   * Set a bunch of parameters at once
   *
   * @param array $attributes The attributes to add to the existing ones
   *
   * @return FormerObject
   */
  public function setAttributes($attributes)
  {
    $this->attributes = array_merge($this->attributes, (array) $attributes);

    return $this;
  }

  /**
   * Get all attributes
   *
   * @return array
   */
  public function getAttributes()
  {
    return $this->attributes;
  }

  /**
   * Replace all attributes with the provided array
   *
   * @param array $attributes The attributes to replace with
   *
   * @return FormerObject
   */
  public function replaceAttributes($attributes)
  {
    $this->attributes = (array) $attributes;

    return $this;
  }

  /**
   * Add one or more classes to the current field
   *
   * @param string $class The class to add
   */
  public function addClass($class)
  {
    if(is_array($class)) $class = implode(' ', $class);

    // Create class attribute if it isn't already
    if (!isset($this->attributes['class'])) {
      $this->attributes['class'] = null;
    }

    // Prevent adding a class twice
    $classes = explode(' ', $this->attributes['class']);
    if (!in_array($class, $classes)) {
      $this->attributes['class'] = trim($this->attributes['class']. ' ' .$class);
    }

    return $this;
  }
}
