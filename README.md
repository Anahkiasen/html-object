HTMLObject
===========

A set of classes to create and manipulate HTML objects abstractions. HTMLObject can be used both way :

### Static calls to the classes

```php
// <p class="foobar">text</p>
echo Element::p('text')->class('foobar');

// <ul><li>foo</li><li>bar</li></ul>
echo List::ul(array(
  'foo', 'bar',
));

// <a href="#foo" class="btn btn-primary" target="_blank">link</a>
echo Link::create('#foo', 'link')->class('btn btn-success')->blank();
```

### Extending the classes

If one of your classes use specific markup or is an abstraction of a piece of HTML, you can extend the core classes to make it easier to interact with the HTML.
