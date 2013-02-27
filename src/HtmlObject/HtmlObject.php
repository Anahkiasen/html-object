<?php
/**
 * HtmlObject
 *
 * An abstraction of an HTML element
 */
namespace HtmlObject;

use Underscore\Types\Arrays;
use Underscore\Types\String;

class HtmlObject
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
   * Potential children of the element
   * @var array
   */
  protected $children = array();

  /**
   * Creates a basic HtmlObject
   *
   * @param string $element
   * @param string $value
   * @param array  $attributes
   */
  public function __construct($element, $value = null, $attributes = array())
  {
    $this->element    = $element;
    $this->value      = $value;
    $this->attributes = $attributes;
  }

  /**
   * Static alias for constructor
   */
  public static function create($element, $value = null, $attributes = array())
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

  /**
   * Default rendering method
   *
   * @return string
   */
  public function render()
  {
    // Create children
    $content = $this->value;
    if ($this->children) {
      foreach ($this->children as $child) {
        $content .= $child->render();
      }
    }

    return '<'.$this->element.$this->parseAttributes($this->attributes).'>'.$content.'</'.$this->element.'>';
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
   * @return HtmlObject
   */
  public static function __callStatic($method, $parameters)
  {
    $value      = Arrays::get($parameters, 0);
    $attributes = Arrays::get($parameters, 1);

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
    $value = Arrays::get($parameters, 0, 'true');
    $this->setAttribute($method, $value);

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
    return Arrays::get($this->attributes, $attribute);
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
    $this->element = $element;

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

  ////////////////////////////////////////////////////////////////////
  ////////////////////////////// CHILDREN ////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Nests an object withing the current object
   */
  public function nest($element, $value = null, $attributes = array())
  {
    if (!($element instanceof HtmlObject)) {
      $element = new HtmlObject($element, $value, $attributes);
    }
    $this->children[] = $element;

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
    if (!String::contains($this->attributes['class'], $class)) {
      $this->attributes['class'] = trim($this->attributes['class']. ' ' .$class);
    }

    return $this;
  }

  ////////////////////////////////////////////////////////////////////
  ////////////////////////////// HELPERS /////////////////////////////
  ////////////////////////////////////////////////////////////////////

  /**
   * Build a list of HTML attributes from an array
   *
   * @param  array  $attributes
   * @return string
   */
  protected function parseAttributes($attributes)
  {
    $html = array();

    foreach ((array) $attributes as $key => $value) {
      if (is_numeric($key)) $key = $value;
      if (!is_null($value)) {
        $html[] = $key. '="' .$this->entities($value). '"';
      }
    }

    return (count($html) > 0) ? ' '.implode(' ', $html) : '';
  }

  /**
   * Convert HTML characters to HTML entities
   *
   * The encoding in $encoding will be used
   *
   * @param  string $value
   * @return string
   */
  protected function entities($value)
  {
    return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
  }
}