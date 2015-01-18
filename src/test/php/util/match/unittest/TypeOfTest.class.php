<?php namespace util\match\unittest;

use util\match\TypeOf;
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
      ->when('var[]', function($value, $self) {
        $r= 'a:'.sizeof($value).':{';
        foreach ($value as $key => $val) {
          $r.= $self($key).$self($val);
        }
        return $r.'}';
      })
      ->when(null, function($value) { return 'N;'; })
    ;

    $this->assertEquals(
      ['i:1;', 's:4:"Test";', 'a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}', 'N;'],
      [$type(1), $type('Test'), $type([1, 2, 3]), $type(null)]
    );
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= 'Unhandled type string')]
  public function unhandled() {
    $type= new TypeOf();
    $type('Test');
  }
}