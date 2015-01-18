<?php namespace util\match;

/**
 * @test  xp://util.data.match.unittest.ValueOfTest
 */
class ValueOf extends Match {

  static function __static() { }

  /**
   * Creates a condition for a given argument
   *
   * @param  var $arg
   * @return util.data.match.Condition
   */
  protected function conditionOf($arg) {
    return $arg instanceof Condition ? $arg : new IsEqual($arg);
  }

  /**
   * Returns message for exception when the given argument is unhandled
   *
   * @param  var $arg
   * @return string
   */
  protected function unhandledMessage($arg) {
    return 'Unhandled condition '.\xp::stringOf($arg);
  }
}
