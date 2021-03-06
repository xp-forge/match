<?php namespace util\match;

use lang\IllegalArgumentException;

/**
 * @test  xp://util.data.match.unittest.MatchTest
 */
class Match extends Expression {
  private $conditionals= [];

  static function __static() { }

  /**
   * Define handler for a given condition
   *
   * @param  util.data.Condition $condition
   * @param  function(?): var $function
   * @return self
   */
  public function when($condition, $function) {
    $this->conditionals[]= new Conditional($condition, self::$HANDLE->cast($function));
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
