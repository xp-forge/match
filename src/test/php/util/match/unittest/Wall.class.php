<?php namespace util\match\unittest;

class Wall extends \lang\Object {
  private $name, $type;

  /**
   * Creates a new wall
   *
   * @param  string $name
   * @param  util.match.unittest.Types $type
   */
  public function __construct($name, Types $type) {
    $this->name= $name;
    $this->type= $type;
  }

  /** @return string */
  public function name() { return $this->name; }

  /** @return util.match.unittest.Types */
  public function type() { return $this->type; }
}
