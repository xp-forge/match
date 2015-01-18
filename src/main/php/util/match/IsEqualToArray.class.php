<?php namespace util\match;

use util\Objects;

/**
 * Compares arrays
 */
class IsEqualToArray extends \lang\Object implements Condition {
  private $value;

  /**
   * Creates a new `IsEqualToArray` condition.
   *
   * @param  var $value
   */
  public function __construct(array $value) {
    $this->value= $value;
  }

  /**
   * Returns true if a given value matches this `IsEqualToArray` condition.
   *
   * @param  var $value
   * @return bool
   */
  public function matches($value) {
    return Objects::equal($this->value, $value);
  }
}