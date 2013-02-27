HTMLObject
===========

A set of classes to create and manipulate HTML objects abstractions.

```php
// <p class="foobar">text</p>
echo Element::p('text')->class('foobar');

// <ul><li>foo</li><li>bar</li></ul>
echo List::ul(array(
  'foo', 'bar',
));

// <a href="#foo" target="_blank">link</a>
echo Link::create('#foo', 'link')->blank();
```