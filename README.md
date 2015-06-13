Matching DSL
============

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-forge/match.svg)](http://travis-ci.org/xp-forge/match)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_4plus.png)](http://php.net/)
[![Required PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
[![Required HHVM 3.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/hhvm-3_4plus.png)](http://hhvm.com/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/match/version.png)](https://packagist.org/packages/xp-forge/match)

Fluent API for matching.

Matching
--------
The following outputs two lines, *My Team 🔒* and *Developers*:

```php
Sequence::of([new Group('My Team', Types::$CLOSED), new Group('Developers', Types::$OPEN)])
  ->map((new Match('Group::type'))
    ->when(new IsEqual(Types::$OPEN), function($group) { return $group->name(); })
    ->when(new IsEqual(Types::$CLOSED), function($group) { return $group->name().' 🔒'; })
  )
  ->each('util.cmd.Console::writeLine')
;
```

If the group's `type()` method where to return an unhandled group type, e.g. `Types::$HIDDEN`, an exception would be raised.

Unhandled values
----------------
To handle the default case, use the `otherwise()` method:

```php
$match= (new Match('Group::type'))
  ->when(new IsEqual(Types::$OPEN), function($group) { return $group->name(); })
  ->when(new IsEqual(Types::$CLOSED), function($group) { return $group->name().' 🔒'; })
  ->otherwise(function($group) { return $group->name().' ('.$group->type()->name().')'; })
;
```

Matching values
---------------
For the special case of testing equality, we can use the specialized `ValueOf` matcher:

```php
$kind= (new ValueOf('Event::weekday'))
  ->when(Day::$SATURDAY, function() { return 'Weekend'; })
  ->when(Day::$SUNDAY, function() { return 'Weekend'; })
  ->otherwise(function() { return 'During the week'; })
;

$display= $kind(new Event('Relax', new Date('2015-01-18')));    // `Weekend`
$display= $kind(new Event('Meeting', new Date('2015-01-19')));  // `During the week`
```

Matching types
--------------
The following is a reimplementation of PHP's [serialize](http://php.net/serialize) function (incomplete, but you get the idea):

```php
$serialize= (new TypeOf())
  ->when(Primitive::$INT, function($value) { return 'i:'.$value.';'; })
  ->when(Primitive::$STRING, function($value) { return 's:'.strlen($value).':"'.$value.'";'; })
  ->when(Type::$ARRAY, function($value, $self) {
    $r= 'a:'.sizeof($value).':{';
    foreach ($value as $key => $val) {
      $r.= $self($key).$self($val);
    }
    return $r.'}';
  })
  ->when(null, function() { return 'N;'; })
;

$serialized= $serialize(1);       // `i:1;`
$serialized= $serialize('Test');  // `s:4:"Test";`
$serialized= $serialize([1, 2]);  // `a:2:{i:0;i:1;i:1;i:2;}`
$serialized= $serialize(null);    // `N;`
```

The `Type::$ARRAY` is actually a type union consisting of zero-indexed arrays and maps, both of which are known to PHP as an `array` (in contrast, the XP Framework only speaks of the first as arrays). We use it here for performance reasons since we don't need to distinguish between the two anyways.

Performance
-----------
They `KeyOf` class is a high-performance alternative to the `ValueOf` class although it's restricted to integers and strings (it uses them as keys in its backing map).

```php
// Using native if and comparison
$match= function($value) {
  if (0 === $value) {
    return 'No elements';
  } else if (1 === $value) {
    return 'One element';
  } else {
    return $value.' elements';
  }
};

// Using KeyOf class
$match= (new KeyOf())
  ->when(0, function() { return 'No elements'; })
  ->when(1, function() { return 'One element'; })
  ->otherwise(function($value) { return $value.' elements'; })
;

// Using ValueOf class
$match= (new ValueOf())
  ->when(0, function() { return 'No elements'; })
  ->when(1, function() { return 'One element'; })
  ->otherwise(function($value) { return $value.' elements'; })
;
```

Using 500000 iterations, PHP 5.4 / Windows 8.1:

| *Invocation*  | *Result*         | *Native if* | *KeyOf class*      | *ValueOf class*   |
| ------------- | ---------------- | ----------: | -----------------: | ----------------: |
| `$match(0)`   | `"No elements"`  | 0.283 secs  | 0.386 secs (1.36x) | 0.566 secs (2.00x) |
| `$match(1)`   | `"One element"`  | 0.287 secs  | 0.385 secs (1.34x) | 0.750 secs (2.61x) |
| `$match(2)`   | `"2 elements"`   | 0.386 secs  | 0.500 secs (1.29x) | 0.882 secs (2.28x) |
| `$match(100)` | `"100 elements"` | 0.383 secs  | 0.524 secs (1.37x) | 0.900 secs (2.35x) |


Further reading
---------------
This library was inspired by Scala's patter matching.

* [Scala Tour: Pattern Matching](http://docs.scala-lang.org/tutorials/tour/pattern-matching.html)
* [Instanceof operator and Visitor pattern replacement in Java 8](http://www.nurkiewicz.com/2013/09/instanceof-operator-and-visitor-pattern.html)