<?php namespace util\match\unittest;

use lang\Object;

abstract class ValueTest extends \unittest\TestCase {

  /**
   * Returns arguments for `...($value, $outcome)` tests. Applies a
   * given map on a list of fixtures by a given kind.
   *
   * @param  [:bool] $map Maps kinds to outcome
   * @return var[][]
   */
  protected function values($map= []) {
    $fixtures= [
      'objects'   => [new Object(), $this],
      'null'      => [null],
      'bools'     => [true, false],
      'ints'      => [-1, 0, 1],
      'doubles'   => [-0.5, 0, 0.61],
      'arrays'    => [[1, 2, 3]],
      'maps'      => [['key' => 'value']],
      'empty'     => [[]],
      'functions' => [function() { }]
    ];

    $args= [];
    foreach ($fixtures as $kind => $values) {
      $outcome= isset($map[$kind]) ? $map[$kind] : false;
      foreach ($values as $value) {
        $args[]= [$value, $outcome];
      }
    }
    return $args;
  }
}