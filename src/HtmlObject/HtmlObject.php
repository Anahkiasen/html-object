<?php
/**
 * HtmlObject
 *
 * An abstraction of an HTML tag
 */
namespace HtmlObject;

use Underscore\Types\Arrays;
use Underscore\Types\String;

class HtmlObject
{

  /**
   * The tag name
   * @var string
   */
  protected $name;

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
   * Creates a basic HtmlObject
   *
   * @param string $name       Tag name
   * @param string $value      Value
   * @param array  $attributes Attributes
   */
  public function __construct($name, $value = null, $attributes = array())
  {
    $this->name       = $name;
    $this->value      = $value;
    $this->attributes = $attributes;
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
    return '<'.$this->name.$this->parseAttributes($this->attributes).'>'.$this->value.'</'.$this->name.'>';
  }

  ////////////////////////////////////////////////////////////////////
  /////////////////////////// CORE METHODS ///////////////////////////
  ////////////////////////////////////////////////////////////////////

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

  ////////////////////////////////////////////////////////////////////
  ////////////////////////////// CLASSES /////////////////////////////
  ////////////////////////////////////////////////////////////////////

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

  /**
   * Set all classes at once
   *
   * @param array $classes
   */
  public function replaceClasses($classes)
  {
    $this->attributes['class'] = $classes;

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