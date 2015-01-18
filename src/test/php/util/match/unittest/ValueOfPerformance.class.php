<?php namespace util\match\unittest;

use util\match\ValueOf;

class ValueOfPerformance extends \util\profiling\Measurable {
  private $match, $native;

  public function __construct($method, $args) {
    parent::__construct($method, $args);
    $this->match= (new ValueOf())
      ->when(0, function() { return 'No elements'; })
      ->when(1, function() { return 'One element'; })
      ->otherwise(function($value) { return $value.' elements'; })
    ;

    $this->native= function($value) {
      if (0 === $value) {
        return 'No elements';
      } else if (1 === $value) {
        return 'One element';
      } else {
        return $value.' elements';
      }
    };
  }

  /** @return var[][] */
  public static function values() { return [0, 1, 2]; }

  #[@measure, @values('values')]
  public function match($value) {
    $f= $this->match;
    return $f($value);
  }

  #[@measure, @values('values')]
  public function native($value) {
    $f= $this->native;
    return $f($value);
  }
}