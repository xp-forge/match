<?php namespace util\match\unittest;

use util\match\TypeOf;
use lang\Type;
use lang\IllegalArgumentException;

class TypeOfTest extends \unittest\TestCase {

  #[@test]
  public function can_create() {
    new TypeOf();
  }

  #[@test]
  public function can_create_with_mapping_function() {
    new TypeOf(function($value) { return true; });
  }

  #[@test]
  public function serialize() {
    $type= (new TypeOf())
      ->when('int', function($value) { return 'i:'.$value.';'; })
      ->when('string', function($value) { return 's:'.strlen($value).':"'.$value.'";'; })
      ->when(Type::$ARRAY, function($value, $self) {
        $r= 'a:'.sizeof($value).':{';
        foreach ($value as $key => $val) {
          $r.= $self($key).$self($val);
        }
        return $r.'}';
      })
      ->when(null, function($value) { return 'N;'; })
    ;

    $this->assertEquals(
      ['i:1;', 's:4:"Test";', 'a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}', 'a:1:{s:3:"key";s:5:"value";}', 'N;'],
      [$type(1), $type('Test'), $type([1, 2, 3]), $type(['key' => 'value']), $type(null)]
    );
  }

  #[@test, @values(['lang.Object', 'lang.Generic', parent::class, self::class])]
  public function an_instance($type) {
    $match= (new TypeOf())->when($type, function() { return 'An object!'; });
    $this->assertEquals('An object!', $match($this));
  }

  #[@test]
  public function not_an_instance() {
    $match= (new TypeOf())
      ->when('lang.Object', function() { return 'An object!'; })
      ->otherwise(function() { return 'Unfortunately not.'; })
    ;
    $this->assertEquals('Unfortunately not.', $match(1));
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= 'Unhandled type string')]
  public function unhandled() {
    $type= new TypeOf();
    $type('Test');
  }
}