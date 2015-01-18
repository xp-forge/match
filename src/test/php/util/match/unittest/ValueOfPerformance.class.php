<?php namespace util\match\unittest;

use util\match\ValueOf;

class ValueOfPerformance extends \util\profiling\Measurable {
  private $match, $if, $switch;

  public function __construct($method, $args) {
    parent::__construct($method, $args);
    $this->match= (new ValueOf())
      ->when(0, function() { return 'No elements'; })
      ->when(1, function() { return 'One element'; })
      ->otherwise(function($value) { return $value.' elements'; })
    ;

    $this->if= function($value) {
      if (0 === $value) {
        return 'No elements';
      } else if (1 === $value) {
        return 'One element';
      } else {
        return $value.' elements';
      }
    };

    $this->switch= function($value) {
      switch ($value) {
        case 0: return 'No elements';
        case 1: return 'One element';
        default: return  $value.' elements';
      }
    };
  }

  /** @return var[][] */
  public static function values() { return [0, 1, 2, 100]; }

  #[@measure, @values('values')]
  public function match($value) {
    $f= $this->match;
    return $f($value);
  }

  #[@measure, @values('values')]
  public function if_stmt($value) {
    $f= $this->if;
    return $f($value);
  }

  #[@measure, @values('values')]
  public function swicht_stmt($value) {
    $f= $this->switch;
    return $f($value);
  }
}