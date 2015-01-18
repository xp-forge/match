<?php namespace util\match;

/**
 * Uses `===`
 */
class IsIdentical extends \lang\Object implements Condition {
  private $value;

  /**
   * Creates a new `IsEqualToPrimitive` condition.
   *
   * @param  var $value
   */
  public function __construct($value) {
    $this->value= $value;
  }

  /**
   * Returns true if a given value matches this `IsEqualToPrimitive` condition.
   *
   * @param  var $value
   * @return bool
   */
  public function matches($value) {
    return $this->value === $value;
  }
}