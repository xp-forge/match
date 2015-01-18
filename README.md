Matching DSL
============

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-forge/sequence.svg)](http://travis-ci.org/xp-forge/sequence)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_4plus.png)](http://php.net/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/sequence/version.png)](https://packagist.org/packages/xp-forge/sequence)

Fluent API for matching.

Matching values
---------------
The following outputs two lines, *My Team 🔒* and *Developers*:

```php
Sequence::of([new Group('My Team', Types::$CLOSED), new Group('Developers', Types::$OPEN)])
  ->map((new ValueOf('Group::type'))
    ->when(Types::$OPEN, function($group) { return $group->name(); })
    ->when(Types::$CLOSED, function($group) { return $group->name().' 🔒'; })
  )
  ->each('util.cmd.Console::writeLine')
;
```

If the group's `type()` method where to return an unhandled group type, e.g. `Types::$HIDDEN`, an exception would be raised.

Unhandled values
----------------
To handle the default case, use the `otherwise()` method:

```php
$match= (new ValueOf())
  ->when(0, function($value) { return 'No elements'; })
  ->when(1, function($value) { return 'One element'; })
  ->otherwise(function($value) { return $value.' elements'; })
;

$display= $match(0);  // "No elements"
$display= $match(1);  // "One element"
$display= $match(2);  // "2 elements"
```

Matching types
--------------
The following is a reimplementation of PHP's [serialize](http://php.net/serialize) function (incomplete, but you get the idea):

```php
$match= (new TypeOf())
  ->when('int', function($value) { return 'i:'.$value.';'; })
  ->when('string', function($value) { return 's:'.strlen($value).':"'.$value.'";'; })
  ->when('var[]', function($value, $self) {
    $r= 'a:'.sizeof($value).':{';
    foreach ($value as $key => $val) {
      $r.= $self($key).$self($val);
    }
    return $r.'}';
  })
  ->when(null, function($value) { return 'N;'; })
;

$serialized= $serialize(1);       // `i:1;`
$serialized= $serialize("Test");  // `s:4:"Test";`
$serialized= $serialize([1, 2]);  // `a:2:{i:0;i:1;i:1;i:2;}`
$serialized= $serialize(null);    // `N;`
```
