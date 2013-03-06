HTMLObject
===========

HTMLObject is a set of classes to create and manipulate HTML objects abstractions. HTMLObject can be used both way :

### Static calls to the classes

```php
echo Element::p('text')->class('foobar');
// <p class="foobar">text</p>
```

```php
$list = List::ul(array('foo', 'bar'));

$link = Link::create('#', 'Someone');
$list->getChild(0)->addClass('active')->setValue('by '.$link);
// <ul>
//   <li class="active">foo</li>
//   <li>by <a href="#">Someone</a></li>
// </ul>
```

```php
// <a href="#foo" class="btn btn-primary" target="_blank">link</a>
echo Link::create('#foo', 'link')->class('btn btn-success')->blank();
```

### Adding custom types

It's fairly easy to implement new types in HtmlObject, just extend the core `Tag` class. Here's an exemple for the common icon pattern tag (`<i class="icon-myicon"></i>) :

```php
<?php
class Icon extends HtmlObject\Traits\Tag
{
  public function __constructor($icon)
  {
    // Arguments are $element, $value, $attributes
    $this->setTag('i', null, array('class' => 'icon-'.$icon));

    // Or simply
    $this->element = 'i';
    $this->attributes = array('class' => 'icon-'.$icon);
  }
}

echo new Icon('bookmark') // Will output <i class="icon-bookmark"></i>
```

From there you can even easily create magic methods :

```php
<?php
class Icon extends Tag
{
  public static function __callStatic($method, $parameters)
  {
    return new static($method);
  }
}

echo Icon::bookmark(); // Same output as above
```

### Extending the classes

If one of your classes use specific markup or is an abstraction of a piece of HTML, you can extend the core classes to make it easier to interact with the HTML.

```php
<?php
class UserAvatar extends Tag
{
  public static function make(User $user)
  {
    $avatar = Image::create($user->image);
    $title  = Element::h2($user->name);

    return Element::figure()->nestChildren([
      'title' => $title,
      'image' => $avatar
    ]);
  }
}

$avatar = UserAvatar::make($user)

// Manipulation can then be done through HtmlObject's methods
$avatar->addClass('span4');
$avatar->getChild('image')->alt($user->name);
$avatar->getChild('title')->wrapValue('strong');

echo $avatar;
```

This will output the following :

```html
<figure class="span4">
  <h2><strong>John Doe</strong></h2>
  <img src="users/john-doe.jpg" alt="John Doe">
</figure>
```

If your class use properties that are at meant to be added to the final array of attributes, you can inject them using the `injectProperties` method. Say you have a `Link` class that has an `url` property, you can overwrite the method like this, and the `$this->url` will get added in the `href` attribute :

```php
protected function injectProperties()
{
  return array(
    'href' => $this->url,
  );
}
```