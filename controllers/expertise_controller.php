<?php
Class ExpertiseController {
  private $db = NULL;

  function __construct() {
    $this->db = getDatabaseConnection();
  }
  
  public function getAllExpertise() {
	$expertisearray = array();
	$expertisesql = "SELECT en.id,expertise_name, expertise_group_id, value FROM expertise_names en
              ORDER BY expertise_group_id ASC, expertise_name ASC";
	$db = getDatabaseConnection();
	$stmt = $db->prepare($expertisesql);
    $stmt->execute();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
	  $expertise = new Expertise();
	  $expertise->setId($row['id']);
	  $expertise->setExpertiseGroupId($row['expertise_group_id']);
	  $expertise->setName($row['expertise_name']);
	  $expertise->setValue($row['value']);
	  $expertisearray[] = $expertise;
    }
    
    return $expertisearray;
  }
  
  public function getAllTerrain() {
	$expertisearray = array();
	$expertisesql = "SELECT en.id,expertise_name, expertise_group_id, value FROM expertise_names en
              JOIN expertise_groups eg ON en.expertise_group_id=eg.id
              WHERE eg.expertise_group_name='Terrain'";
	$db = getDatabaseConnection();
	$stmt = $db->prepare($expertisesql);
    $stmt->execute();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
	  $expertise = new Expertise();
	  $expertise->setId($row['id']);
	  $expertise->setExpertiseGroupId($row['expertise_group_id']);
	  $expertise->setName($row['expertise_name']);
	  $expertise->setValue($row['value']);
	  $expertisearray[] = $expertise;
    }
    
    return $expertisearray;
  }
}
