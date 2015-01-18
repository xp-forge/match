<?php namespace util\match;

use lang\FunctionType;
use lang\Generic;
use util\Objects;

/**
 * Uses equality comparison for the given value - which is defined as:
 *
 * 1. If the value is an instance of `lang.Generic`, call its `equals()` method.
 * 2. For arrays, values are compared to the same rule.
 * 3. For maps, keys and values are compared to the same rule. Key order is not relevant.
 * 4. Otherwise, use the identity comparison operator, `===`.
 *
 * Optimizes the cases 1 and 4 and delegates 2 and 3 to the `util.Objects` class.
 *
 * @see   xp://util.Objects#equal
 * @test  xp://util.data.match.unittest.IsEqualTest
 */
class IsEqual extends \lang\Object implements Condition {
  private static $EQUALS;
  private $compare;

  static function __static() {
    self::$EQUALS= FunctionType::forName('function(var): bool');
  }

  /**
   * Creates a new `IsEqual` condition.
   *
   * @param  var $value
   */
  public function __construct($value) {
    if ($value instanceof Generic) {
      $this->compare= self::$EQUALS->cast([$value, 'equals']);
    } else if (is_array($value)) {
      $this->compare= function($cmp) use($value) { return Objects::equal($value, $cmp); };
    } else {
      $this->compare= function($cmp) use($value) { return $value === $cmp; };
    }
  }

  /**
   * Returns true if a given value matches this `IsEqual` condition.
   *
   * @param  var $value
   * @return bool
   */
  public function matches($value) {
    return $this->compare->__invoke($value);
  }
}