HTMLObject
===========

A set of classes to create and manipulate HTML objects abstractions.

```php
Element::p('text')->class('foobar') // <p class="foobar">text</p>

List::ul(array(
  'foo', 'bar',
)) // <ul><li>foo</li><li>bar</li></ul>
```