<?php namespace util\match;

use lang\IllegalArgumentException;

/**
 * @test  xp://util.data.match.unittest.TypeOfTest
 */
class TypeOf extends Match {
  private $null= null, $types= [];

  static function __static() { }

  /**
   * Define handler for a given condition
   *
   * @param  var $type
   * @param  function(?): var $function
   * @return self
   */
  public function when($type, $function) {
    if (null === $type) {
      $this->null= $function;
    } else {
      $this->types[]= new Conditional(new IsInstance($type), self::$HANDLE->cast($function));
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

    if (null === $value) {
      if ($this->null) {
        $f= $this->null;
        return $f($value, $this);
      }
    } else {
      foreach ($this->types as $conditional) {
        if ($conditional->condition->matches($expr)) {
          $f= $conditional->handle;
          return $f($value, $this);
        }
      }
    }

    if ($this->otherwise) {
      $f= $this->otherwise;
      return $f($value, $this);
    } else {
      throw new IllegalArgumentException('Unhandled type '.\xp::typeOf($expr));
    }
  }
}
