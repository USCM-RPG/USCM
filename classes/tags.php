<?php
Class Tag extends DbEntity {
  private $name = NULL;
  
  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }
}
