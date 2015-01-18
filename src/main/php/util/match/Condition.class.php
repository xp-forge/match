<?php namespace util\match;

interface Condition {

  /**
   * Returns true if a given value matches this condition.
   *
   * @param  var $value
   * @return bool
   */
  public function matches($value);
}