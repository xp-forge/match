<?php namespace util\match;

use lang\Generic;

/**
 * Uses `lang.Generic::equals()`
 */
class IsEqualToObject extends \lang\Object implements Condition {
  private $value;

  /**
   * Creates a new `IsEqualToObject` condition.
   *
   * @param  lang.Generic $value
   */
  public function __construct(Generic $value) {
    $this->value= $value;
  }

  /**
   * Returns true if a given value matches this `IsEqualToObject` condition.
   *
   * @param  var $value
   * @return bool
   */
  public function matches($value) {
    return $this->value->equals($value);
  }
}