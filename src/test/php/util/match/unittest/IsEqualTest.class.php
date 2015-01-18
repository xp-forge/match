<?php namespace util\match\unittest;

use util\match\IsEqual;

class IsEqualTest extends ValueTest {

  #[@test, @values('values')]
  public function can_create($value) {
    new IsEqual($value);
  }

  #[@test, @values('values')]
  public function values_are_equal_to_themselves($value) {
    $this->assertTrue((new IsEqual($value))->matches($value));
  }

  #[@test, @values([
  #  ['Test', true],
  #  ['Any other value', false]
  #])]
  public function calls_equals_method($value, $outcome) {
    $object= newinstance('lang.Object', [], [
      'equals' => function($cmp) { return 'Test' === $cmp; }
    ]);
    $this->assertEquals($outcome, (new IsEqual($object))->matches($value));
  }

  #[@test, @values([
  #  [0, true],
  #  [0.0, false], [null, false], [false, false], ['', false]
  #])]
  public function uses_identical_comparison($value, $outcome) {
    $this->assertEquals($outcome, (new IsEqual(0))->matches($value));
  }

  #[@test, @values([
  #  [[1, 2, 3], true],
  #  [[], false], [[3, 2, 1], false], [['key' => 'value'], false]
  #])]
  public function can_compare_arrays($value, $outcome) {
    $this->assertEquals($outcome, (new IsEqual([1, 2, 3]))->matches($value));
  }

  #[@test, @values([
  #  [['Test'], true],
  #  [[], false], [['Any other value'], false]
  #])]
  public function calls_equals_method_in_arrays($value, $outcome) {
    $object= newinstance('lang.Object', [], [
      'equals' => function($cmp) { return 'Test' === $cmp; }
    ]);
    $this->assertEquals($outcome, (new IsEqual([$object]))->matches($value));
  }

  #[@test, @values([
  #  [['a' => 1, 'b' => 2], true], [['b' => 2, 'a' => 1], true],
  #  [[], false], [[3, 2, 1], false], [['a' => 1], false], [['key' => 'value'], false]
  #])]
  public function can_compare_maps($value, $outcome) {
    $this->assertEquals($outcome, (new IsEqual(['a' => 1, 'b' => 2]))->matches($value));
  }

  #[@test, @values([
  #  [['key' => 'Test'], true],
  #  [[], false], [['key' => 'Any other value'], false]
  #])]
  public function calls_equals_method_in_maps($value, $outcome) {
    $object= newinstance('lang.Object', [], [
      'equals' => function($cmp) { return 'Test' === $cmp; }
    ]);
    $this->assertEquals($outcome, (new IsEqual(['key' => $object]))->matches($value));
  }
}