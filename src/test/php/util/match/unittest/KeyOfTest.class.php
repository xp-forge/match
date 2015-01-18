<?php namespace util\match\unittest;

use util\match\KeyOf;
use lang\IllegalArgumentException;

class KeyOfTest extends \unittest\TestCase {

  #[@test]
  public function can_create() {
    new KeyOf();
  }

  #[@test]
  public function can_create_with_mapping_function() {
    new KeyOf(function($value) { return true; });
  }

  #[@test]
  public function wording_for_numbers() {
    $match= (new KeyOf())
      ->when(0, function() { return 'No elements'; })
      ->when(1, function() { return 'One element'; })
      ->otherwise(function($value) { return $value.' elements'; })
    ;

    $this->assertEquals(
      ['No elements', 'One element', '2 elements'],
      [$match(0), $match(1), $match(2)]
    );
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= 'Unhandled key "Test"')]
  public function unhandled() {
    $match= new KeyOf();
    $match('Test');
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= 'Illegal key type')]
  public function illegal_key_type() {
    (new KeyOf())->when($this, function() { });
  }
}