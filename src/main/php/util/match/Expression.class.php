<?php namespace util\match;

use lang\FunctionType;
use lang\IllegalArgumentException;

/**
 * Match expression base class
 *
 * @see   xp://util.data.match.ValueOf
 * @see   xp://util.data.match.TypeOf
 */
abstract class Expression extends \lang\Object {
  protected static $MAP, $HANDLE;
  protected $mapping, $otherwise= null;

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
    $this->mapping= self::$MAP->cast($map);
  }

  /**
   * Define handler for a given condition
   *
   * @param  var $condition
   * @param  function(?): var $function
   * @return self
   */
  public abstract function when($condition, $function);

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
  public abstract function __invoke($value);
}
