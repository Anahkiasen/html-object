<?php
namespace HtmlObject\Traits;

use HtmlObject\Element;

/**
 * An abstraction of an HTML element.
 */
abstract class Tag extends TreeObject
{
    /**
     * The element name.
     *
     * @type string
     */
    protected $element;

    /**
     * The object's value.
     *
     * @type string|null|Tag
     */
    protected $value;

    /**
     * The object's attribute.
     *
     * @type array
     */
    protected $attributes = array();

    /**
     * Whether the element is self closing.
     *
     * @type boolean
     */
    protected $isSelfClosing = false;

    /**
     * Whether the current tag is opened or not.
     *
     * @type boolean
     */
    protected $isOpened = false;

    /**
     * A list of class properties to be added to attributes.
     *
     * @type array
     */
    protected $injectedProperties = array('value');

    // Configuration options ----------------------------------------- /

    /**
     * The base configuration inherited by classes.
     *
     * @type array
     */
    public static $config = array(
        'doctype' => 'html',
    );

    ////////////////////////////////////////////////////////////////////
    //////////////////////////// CORE METHODS //////////////////////////
    ////////////////////////////////////////////////////////////////////

    /**
     * Set up a new tag.
     *
     * @param string      $element    Its element
     * @param string|null $value      Its value
     * @param array       $attributes Its attributes
     */
    protected function setTag($element, $value = null, $attributes = array())
    {
        $this->setValue($value);
        $this->setElement($element);
        $this->replaceAttributes($attributes);
    }

    /**
     * Wrap the Element in another element.
     *
     * @param string|Element $element The element's tag
     *
     * @return Element
     */
    public function wrapWith($element, $name = null)
    {
        if (!$element instanceof Tag) {
            $element = Element::create($element);
        }
        if ($this->parent) {
            $this->parent->nest($element, $name);
            $children = $this->parent->children;
            unset($children[$this->parentIndex]);
            $this->parent->children = $children;
            $name                   = $this->parentIndex;
        }
        $element->nest($this, $name);

        return $this;
    }

    /**
     * Render on string conversion.
     *
     * @return string|null
     */
    public function __toString()
    {
        return $this->render();
    }

    ////////////////////////////////////////////////////////////////////
    ///////////////////////// ELEMENT RENDERING ////////////////////////
    ////////////////////////////////////////////////////////////////////

    /**
     * Opens the Tag.
     *
     * @return string|null
     */
    public function open()
    {
        $this->isOpened = true;

        // If self closing, put value as attribute
        foreach ($this->injectProperties() as $attribute => $property) {
            if (!$this->isSelfClosing && $attribute == 'value') {
                continue;
            }
            if (is_null($property) && !is_empty($property)) {
                continue;
            }
            $this->attributes[$attribute] = $property;
        }

        // Invisible tags
        if (!$this->element) {
            return;
        }

        return '<'.$this->element.Helpers::parseAttributes($this->attributes).$this->getTagCloser();
    }

    /**
     * Open the tag tree on a particular child.
     *
     * @param string $onChild The child's key
     *
     * @return string
     */
    public function openOn($onChildren)
    {
        $onChildren = explode('.', $onChildren);
        $element    = $this->open();
        $element .= $this->value;
        $subject = $this;

        foreach ($onChildren as $onChild) {
            foreach ($subject->getChildren() as $childName => $child) {
                if ($childName != $onChild) {
                    $element .= $child;
                } else {
                    $subject = $child;
                    $element .= $child->open();
                    break;
                }
            }
        }

        return $element;
    }

    /**
     * Check if the tag is opened.
     *
     * @return boolean
     */
    public function isOpened()
    {
        return $this->isOpened;
    }

    /**
     * Returns the Tag's content.
     *
     * @return string
     */
    public function getContent()
    {
        $value = $this->value;
        if ($value instanceof Tag) {
            $value = $value->render();
        }

        return $value.$this->renderChildren();
    }

    /**
     * Close the Tag.
     *
     * @return string|null
     */
    public function close()
    {
        $this->isOpened = false;
        $openedOn       = null;
        $element        = null;

        foreach ($this->children as $childName => $child) {
            if ($child->isOpened && !$child->isSelfClosing) {
                $openedOn = $childName;
                $element .= $child->close();
            } elseif ($openedOn && $child->isAfter($openedOn)) {
                $element .= $child;
            }
        }

        // Invisible tags
        if (!$this->element) {
            return;
        }

        return $element .= '</'.$this->element.'>';
    }

    /**
     * Default rendering method.
     *
     * @return string|null
     */
    public function render()
    {
        // If it's a self closing tag
        if ($this->isSelfClosing) {
            return $this->open();
        }

        return $this->open().$this->getContent().$this->close();
    }

    /**
     * Get the preferred way to close a tag.
     *
     * @return string
     */
    protected function getTagCloser()
    {
        if ($this->isSelfClosing && static::$config['doctype'] == 'xhtml') {
            return ' />';
        }

        return '>';
    }

    ////////////////////////////////////////////////////////////////////
    /////////////////////////// MAGIC METHODS //////////////////////////
    ////////////////////////////////////////////////////////////////////

    /**
     * Dynamically set attributes.
     *
     * @param string $method     An attribute
     * @param array  $parameters Its value(s)
     *
     * @return $this
     */
    public function __call($method, $parameters)
    {
        // Replace underscores
        $method = Helpers::hyphenated($method);
        $method = str_replace('_', '-', $method);

        // Get value and set it
        $value         = Helpers::arrayGet($parameters, 0, 'true');
        $this->$method = $value;

        return $this;
    }

    /**
     * Dynamically set an attribute.
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
     * Get an attribute or a child.
     *
     * @param string $item The desired child/attribute
     *
     * @return mixed
     */
    public function __get($item)
    {
        if (array_key_exists($item, $this->attributes)) {
            return $this->attributes[$item];
        }

        // Get a child by snake case
        $child = preg_replace_callback('/([A-Z])/', function ($match) {
            return '.'.strtolower($match[1]);
        }, $item);
        $child = $this->getChild($child);

        return $child;
    }

    ////////////////////////////////////////////////////////////////////
    //////////////////////////////// VALUE /////////////////////////////
    ////////////////////////////////////////////////////////////////////

    /**
     * Changes the Tag's element.
     *
     * @param string $element
     *
     * @return $this
     */
    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Change the object's value.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        if (is_array($value)) {
            $this->nestChildren($value);
        } else {
            $this->value = $value;
        }

        return $this;
    }

    /**
     * Wrap the value in a tag.
     *
     * @param string $tag The tag
     *
     * @return $this
     */
    public function wrapValue($tag)
    {
        $this->value = Element::create($tag, $this->value);

        return $this;
    }

    /**
     * Get the value.
     *
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get all the children as a string.
     *
     * @return string
     */
    protected function renderChildren()
    {
        $children = $this->children;
        foreach ($children as $key => $child) {
            if ($child instanceof Tag) {
                $children[$key] = $child->render();
            }
        }

        return implode($children);
    }

    ////////////////////////////////////////////////////////////////////
    //////////////////////////// ATTRIBUTES ////////////////////////////
    ////////////////////////////////////////////////////////////////////

    /**
     * Return an array of protected properties to bind as attributes.
     *
     * @return array
     */
    protected function injectProperties()
    {
        $properties = array();

        foreach ($this->injectedProperties as $property) {
            if (!isset($this->$property)) {
                continue;
            }

            $properties[$property] = $this->$property;
        }

        return $properties;
    }

    /**
     * Set an attribute.
     *
     * @param string      $attribute An attribute
     * @param string|null $value     Its value
     *
     * @return $this
     */
    public function setAttribute($attribute, $value = null)
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * Set a bunch of parameters at once.
     *
     * @param array $attributes The attributes to add to the existing ones
     *
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = array_merge($this->attributes, (array) $attributes);

        return $this;
    }

    /**
     * Get all attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get an attribute.
     *
     * @param string $attribute
     *
     * @return mixed
     */
    public function getAttribute($attribute)
    {
        return Helpers::arrayGet($this->attributes, $attribute);
    }

    /**
     * Remove an attribute.
     *
     * @param string $attribute
     *
     * @return $this
     */
    public function removeAttribute($attribute)
    {
        if (array_key_exists($attribute, $this->attributes)) {
            unset($this->attributes[$attribute]);
        }

        return $this;
    }

    /**
     * Replace all attributes with the provided array.
     *
     * @param array $attributes The attributes to replace with
     *
     * @return $this
     */
    public function replaceAttributes($attributes)
    {
        $this->attributes = (array) $attributes;

        return $this;
    }

    /**
     * Add one or more classes to the current field.
     *
     * @param string $class The class(es) to add
     *
     * @return $this
     */
    public function addClass($class)
    {
        if (is_array($class)) {
            $class = implode(' ', $class);
        }

        // Create class attribute if it isn't already
        if (!isset($this->attributes['class'])) {
            $this->attributes['class'] = null;
        }

        // Prevent adding a class twice
        $classes = explode(' ', $this->attributes['class']);
        if (!in_array($class, $classes)) {
            $this->attributes['class'] = trim($this->attributes['class'].' '.$class);
        }

        return $this;
    }

    /**
     * Remove one or more classes to the current field.
     *
     * @param string $classes The class(es) to remove
     *
     * @return $this
     */
    public function removeClass($classes)
    {
        if (!is_array($classes)) {
            $classes = explode(' ', $classes);
        }

        $thisClasses = explode(' ', Helpers::arrayGet($this->attributes, 'class'));
        foreach ($classes as $class) {
            $exists = array_search($class, $thisClasses);
            if (!is_null($exists)) {
                unset($thisClasses[$exists]);
            }
        }

        $this->attributes['class'] = implode(' ', $thisClasses);

        return $this;
    }
}
