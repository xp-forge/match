<?php namespace util\match\unittest;

use util\match\ValueOf;
use lang\IllegalArgumentException;

class ValueOfTest extends \unittest\TestCase {

  #[@test]
  public function can_create() {
    new ValueOf();
  }

  #[@test]
  public function can_create_with_mapping_function() {
    new ValueOf(function($value) { return true; });
  }

  #[@test]
  public function wording_for_numbers() {
    $match= (new ValueOf())
      ->when(0, function() { return 'No elements'; })
      ->when(1, function() { return 'One element'; })
      ->otherwise(function($value) { return $value.' elements'; })
    ;

    $this->assertEquals(
      ['No elements', 'One element', '2 elements'],
      [$match(0), $match(1), $match(2)]
    );
  }

  #[@test]
  public function matching_enum_members() {
    $match= (new ValueOf('util.match.unittest.Wall::type'))
      ->when(Types::$OPEN, function($wall) { return $wall->name(); })
      ->when(Types::$CLOSED, function($wall) { return $wall->name().' 🔒'; })
    ;

    $this->assertEquals(
      ['My Team 🔒', 'Developers'],
      [$match(new Wall('My Team', Types::$CLOSED)), $match(new Wall('Developers', Types::$OPEN))]
    );
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= 'Unhandled condition "Test"')]
  public function unhandled() {
    $match= new ValueOf();
    $match('Test');
  }
}