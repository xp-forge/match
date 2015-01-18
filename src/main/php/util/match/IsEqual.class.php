<?php namespace util\match;

use lang\Generic;

/**
 * Uses equality comparison for the given value - which is defined as:
 *
 * 1. If the value is an instance of `lang.Generic`, call its `equals()` method.
 * 2. For arrays, values are compared to the same rule.
 * 3. For maps, keys and values are compared to the same rule. Key order is not relevant.
 * 4. Otherwise, use the identity comparison operator, `===`.
 *
 * @see   xp://util.match.IsEqualToPrimitive
 * @see   xp://util.match.IsEqualToObject
 * @see   xp://util.match.IsEqualToArray
 * @test  xp://util.match.unittest.IsEqualTest
 */
class IsEqual extends \lang\Object implements Condition {
  private $delegate;

  /**
   * Creates a new `IsEqual` condition.
   *
   * @param  var $value
   */
  public function __construct($value) {
    if ($value instanceof Generic) {
      $this->delegate= new IsEqualToObject($value);
    } else if (is_array($value)) {
      $this->delegate= new IsEqualToArray($value);
    } else {
      $this->delegate= new IsEqualToPrimitive($value);
    }
  }

  /**
   * Returns true if a given value matches this `IsEqual` condition.
   *
   * @param  var $value
   * @return bool
   */
  public function matches($value) {
    return $this->delegate->matches($value);
  }
}