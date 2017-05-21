<?php namespace util\match;

use lang\IllegalArgumentException;

/**
 * @test  xp://util.data.match.unittest.KeyOfTest
 */
class KeyOf extends Expression {
  private $hash= [];

  static function __static() { }

  /**
   * Define handler for a given key
   *
   * @param  var $key Either a string or an integer
   * @param  function(?): var $function
   * @return self
   */
  public function when($key, $function) {
    if (is_string($key) || is_int($key)) {
      $this->hash[$key]= self::$HANDLE->cast($function);
    } else {
      throw new IllegalArgumentException('Illegal key type '.typeof($key)->getName());
    }
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

    if (isset($this->hash[$expr])) {
      return $this->hash[$expr]($value, $this);
    } else if ($this->otherwise) {
      $f= $this->otherwise;
      return $f($value, $this);
    } else {
      throw new IllegalArgumentException('Unhandled key '.\xp::stringOf($expr));
    }
  }
}
