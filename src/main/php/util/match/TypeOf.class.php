<?php namespace util\match;

use lang\IllegalArgumentException;
use lang\Type;

/**
 * @test  xp://util.data.match.unittest.TypeOfTest
 */
class TypeOf extends Expression {
  private static $GETTYPE= [
    'int'    => 'integer',
    'double' => 'double',
    'array'  => 'array',
    'string' => 'string',
    'bool'   => 'boolean'
  ];
  private $instance= [], $primitive= [];

  static function __static() { }

  /**
   * Define handler for a given condition
   *
   * @param  var $type Either NULL, a string type reference or a `lang.Type` instance.
   * @param  function(?): var $function
   * @return self
   */
  public function when($type, $function) {
    if (null === $type) {
      $this->primitive[null]= self::$HANDLE->cast($function);
    } else {
      $t= $type instanceof Type ? $type : Type::forName($type);
      $name= $t->getName();
      if (isset(self::$GETTYPE[$name])) {
        $this->primitive[self::$GETTYPE[$name]]= self::$HANDLE->cast($function);
      } else {
        $this->instance[]= new Conditional(new IsInstance($t), self::$HANDLE->cast($function));
      }
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

    if (null === $expr) {
      $type= null;
    } else {
      $type= gettype($expr);
    }

    if (isset($this->primitive[$type])) {
      return $this->primitive[$type]($value, $this);
    } else {
      foreach ($this->instance as $conditional) {
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
