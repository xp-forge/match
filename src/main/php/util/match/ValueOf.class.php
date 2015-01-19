<?php namespace util\match;

use lang\IllegalArgumentException;

/**
 * @test  xp://util.data.match.unittest.ValueOfTest
 */
class ValueOf extends Match {
  private $conditionals= [];

  static function __static() { }

  /**
   * Define handler for a given condition
   *
   * @param  var $condition
   * @param  function(?): var $function
   * @return self
   */
  public function when($condition, $function) {
    $this->conditionals[]= new Conditional(
      $condition instanceof Condition ? $condition : new IsEqual($condition),
      self::$HANDLE->cast($function)
    );
    return $this;
  }

  /**
   * Invoke match. Returns the handler's result for the first condition to 
   * match the given value. If no condition matched and no default handler
   * was installed, an exception is raised.
   *
   * @param  var $value
   * @return var
   * @throws lang.IllegalArgumentException
   */
  public function __invoke($value) {
    if ($this->mapping) {
      $f= $this->mapping;
      $expr= $f($value);
    } else {
      $expr= $value;
    }

    foreach ($this->conditionals as $conditional) {
      if ($conditional->condition->matches($expr)) {
        $f= $conditional->handle;
        return $f($value, $this);
      }
    }

    if ($this->otherwise) {
      $f= $this->otherwise;
      return $f($value, $this);
    } else {
      throw new IllegalArgumentException('Unhandled value '.\xp::stringOf($expr));
    }
  }
}
