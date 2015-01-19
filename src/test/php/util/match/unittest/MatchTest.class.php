<?php namespace util\match\unittest;

use util\match\Match;
use util\match\IsEqual;
use util\match\IsIdentical;
use lang\IllegalArgumentException;

class MatchTest extends \unittest\TestCase {

  #[@test]
  public function can_create() {
    new Match();
  }

  #[@test]
  public function can_create_with_mapping_function() {
    new Match(function($value) { return true; });
  }

  #[@test]
  public function wording_for_numbers() {
    $match= (new Match())
      ->when(new IsIdentical(0), function() { return 'No elements'; })
      ->when(new IsIdentical(1), function() { return 'One element'; })
      ->otherwise(function($value) { return $value.' elements'; })
    ;

    $this->assertEquals(
      ['No elements', 'One element', '2 elements'],
      [$match(0), $match(1), $match(2)]
    );
  }

  #[@test]
  public function matching_enum_members() {
    $match= (new Match('util.match.unittest.Wall::type'))
      ->when(new IsEqual(Types::$OPEN), function($wall) { return $wall->name(); })
      ->when(new IsEqual(Types::$CLOSED), function($wall) { return $wall->name().' 🔒'; })
    ;

    $this->assertEquals(
      ['My Team 🔒', 'Developers'],
      [$match(new Wall('My Team', Types::$CLOSED)), $match(new Wall('Developers', Types::$OPEN))]
    );
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= 'Unhandled value "Test"')]
  public function unhandled() {
    $match= new Match();
    $match('Test');
  }
}