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
$list->getChild(0)->addClass('active')->setValue('by ')->nest($link);
// <ul>
//   <li class="active">foo</li>
//   <li>by <a href="#">Someone</a></li>
// </ul>
```

```php
// <a href="#foo" class="btn btn-primary" target="_blank">link</a>
echo Link::create('#foo', 'link')->class('btn btn-success')->blank();
```

### Extending the classes

If one of your classes use specific markup or is an abstraction of a piece of HTML, you can extend the core classes to make it easier to interact with the HTML.

```php
<?php
class UserAvatar extends Element
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
