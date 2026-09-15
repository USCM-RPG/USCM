<?php

class Platoon extends DbEntity {
  private $longName = "";
  private $shortName = "";
  private $certificates = NULL;

  public function getName() {
    return $this->longName;
  }

  public function setName($name) {
    $this->longName = $name;
  }

  public function getShortName() {
    return $this->shortName;
  }

  public function setShortName($name) {
    $this->shortName = $name;
  }

  /**
   * @return Certificate[]
   */
  public function getCertificates() {
    return $this->certificates;
  }

  /**
   *
   * @param Certificate[] $certificates
   */
  public function setCertificates($certificates) {
    $this->certificates = $certificates;
  }
}
