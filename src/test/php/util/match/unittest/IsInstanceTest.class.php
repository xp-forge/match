<?php namespace util\match\unittest;

use util\match\IsInstance;
use lang\XPClass;
use lang\Primitive;
use lang\ArrayType;
use lang\MapType;
use lang\FunctionType;
use lang\Type;
use lang\IllegalStateException;

class IsInstanceTest extends ValueTest {

  /** @return var[][] */
  private function types() {
    return [
      ['lang.Generic'], ['lang\\Generic'], [XPClass::forName('lang.Generic')],
      ['lang.Object'], ['lang\\Object'], [XPClass::forName('lang.Object')],
      ['int[]'], [new ArrayType('int')],
      ['[:string]'], [new MapType('string')],
      ['string'], [Primitive::$STRING],
      ['function(): var'], [new FunctionType([], Type::$VAR)]
    ];
  }

  #[@test, @values('types')]
  public function can_create($type) {
    new IsInstance($type);
  }

  #[@test, @expect(IllegalStateException::class)]
  public function null_is_not_a_type() {
    new IsInstance(null);
  }

  #[@test, @values(source= 'values', args= [['objects' => true]])]
  public function is_instance_of_generic($value, $outcome) {
    $this->assertEquals($outcome, (new IsInstance('lang.Generic'))->matches($value));
  }

  #[@test, @values(source= 'values', args= [['objects' => true]])]
  public function is_instance_of_object($value, $outcome) {
    $this->assertEquals($outcome, (new IsInstance('lang.Object'))->matches($value));
  }

  #[@test, @values(source= 'values', args= [['arrays' => true, 'empty' => true]])]
  public function is_instance_of_int_array($value, $outcome) {
    $this->assertEquals($outcome, (new IsInstance('int[]'))->matches($value));
  }

  #[@test, @values(source= 'values', args= [['maps' => true, 'empty' => true]])]
  public function is_instance_of_string_map($value, $outcome) {
    $this->assertEquals($outcome, (new IsInstance('[:string]'))->matches($value));
  }

  #[@test, @values(source= 'values', args= [['strings' => true]])]
  public function is_instance_of_string($value, $outcome) {
    $this->assertEquals($outcome, (new IsInstance('string'))->matches($value));
  }

  #[@test, @values(source= 'values', args= [['functions' => true]])]
  public function is_instance_of_function($value, $outcome) {
    $this->assertEquals($outcome, (new IsInstance('function(): var'))->matches($value));
  }
}