<?php
Class CharacterTrait extends DbEntity {
  private $name = NULL;
  private $description = NULL;
  private $version = NULL;

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getDescription() {
    return $this->description;
  }

  public function setDescription($description) {
    $this->description = $description;
  }

  public function getVersion() {
    return $this->version;
  }

  public function setVersion($version) {
    $this->version = $version;
  }
}
