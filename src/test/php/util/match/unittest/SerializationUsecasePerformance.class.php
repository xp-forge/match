<?php namespace util\match\unittest;

use util\match\TypeOf;
use lang\Type;

class SerializationUsecasePerformance extends \util\profiling\Measurable {
  private $type, $native;

  public function __construct($method, $args) {
    parent::__construct($method, $args);
    $this->type= (new TypeOf())
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

    $this->native= function($value) {
      if (null === $value) {
        return 'N;';
      } else if (is_int($value)) {
        return 'i:'.$value.';';
      } else if (is_string($value)) {
        return 's:'.strlen($value).':"'.$value.'";';
      } else if (is_array($value)) {
        $r= 'a:'.sizeof($value).':{';
        $self= $this->native;
        foreach ($value as $key => $val) {
          $r.= $self($key).$self($val);
        }
        return $r.'}';
      }
    };
  }

  /** @return var[][] */
  public static function values() { return [[null], [1], ['Test'], [[1, 2, 3]]]; }

  #[@measure, @values('values')]
  public function type_match($value) {
    $f= $this->type;
    return $f($value);
  }

  #[@measure, @values('values')]
  public function native_impl($value) {
    $f= $this->native;
    return $f($value);
  }
}