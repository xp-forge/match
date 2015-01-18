<?php namespace util\match;

class Conditional extends \lang\Object {
  public $condition, $handle;

  public function __construct($condition, $handle) {
    $this->condition= $condition;
    $this->handle= $handle;
  }
}