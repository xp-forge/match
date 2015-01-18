<?php namespace util\match;

use lang\Type; 

/**
 * Uses instance of tests to match values.
 *
 * @see   xp://lang.Type#isInstance
 * @test  xp://util.data.match.unittest.IsInstanceTest
 */
class IsInstance extends \lang\Object implements Condition {
  private $type;

  /**
   * Creates a new `IsInstance` condition.
   *
   * @param  var $type Either a lang.Type instance or a string
   */
  public function __construct($type) {
    $this->type= $type instanceof Type ? $type : Type::forName($type);
  }

  /**
   * Returns true if a given value matches this `IsInstance` condition.
   *
   * @param  var $value
   * @return bool
   */
  public function matches($value) {
    return $this->type->isInstance($value);
  }
}