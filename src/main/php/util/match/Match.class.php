<?php namespace util\match;

use lang\FunctionType;
use lang\IllegalArgumentException;

/**
 * Match base class
 *
 * @see   xp://util.data.match.ValueOf
 * @see   xp://util.data.match.TypeOf
 */
abstract class Match extends \lang\Object {
  private static $MAP, $HANDLE;
  private $map, $when= [], $otherwise= null;

  static function __static() {
    self::$MAP= FunctionType::forName('function(var): var');
    self::$HANDLE= FunctionType::forName('function(?): var');
  }

  /**
   * Creates a new match. Optionally accepts a mapping function to retrieve
   * expression to test on.
   *
   * @param  function(var): var $map
   */
  public function __construct($map= null) {
    $this->map= self::$MAP->cast($map);
  }

  /**
   * Creates a condition for a given argument
   *
   * @param  var $arg
   * @return util.data.match.Condition
   */
  protected abstract function conditionOf($arg);

  /**
   * Returns message for exception when the given argument is unhandled
   *
   * @param  var $arg
   * @return string
   */
  protected abstract function unhandledMessage($arg);

  /**
   * Define handler for a given condition
   *
   * @param  var $condition
   * @param  function(?): var $function
   * @return self
   */
  public function when($condition, $function) {
    $this->when[]= [$this->conditionOf($condition), self::$HANDLE->cast($function)];
    return $this;
  }

  /**
   * Define handler for default case
   *
   * @param  var $condition
   * @param  function(?): var $function
   * @return self
   */
  public function otherwise($function) {
    $this->otherwise= self::$HANDLE->cast($function);
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
    if ($this->map) {
      $expr= $this->map->__invoke($value);
    } else {
      $expr= $value;
    }

    foreach ($this->when as $when) {
      if ($when[0]->matches($expr)) return $when[1]->__invoke($value, $this);
    }

    if ($this->otherwise) {
      return $this->otherwise->__invoke($value);
    } else {
      throw new IllegalArgumentException($this->unhandledMessage($expr));
    }
  }
}
