<?php
Class TagController {
  private $db = NULL;

  function __construct() {
    $this->db = getDatabaseConnection();
  }

  /**
   *
   * @param string $constraint Where clause to use in query
   * @return Medal[]
   */
  public function getAllTags() {
    $sql = "SELECT id, tag " .
        "FROM uscm_tags";
    $sql = $sql . " ORDER BY tag ASC ";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $tags = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $tag = new Tag();
      $tag->setId($row['id']);
      $tag->setName($row['tag']);
      $tags[] = $tag;
    }
    return $tags;
  }
  
   public function getTagsForMission($missionId) {
    $sql = "SELECT tg.id, tag " .
        "FROM uscm_tags AS tg " .
        "INNER JOIN uscm_mission_tags AS m ON m.tagid = tg.id " .
        "WHERE m.missionid = :cid";
    $sql = $sql . " ORDER BY tag ASC ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':cid', $missionId, PDO::PARAM_INT);
    $stmt->execute();
    $tags = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $tag = new Tag();
      $tag->setId($row['id']);
      $tag->setName($row['tag']);
      $tags[] = $tag;
    }
    return $tags;
  }

#funktion för att hämta alla missions med en specifik tag
public function getMissionsForTag($tagId) {
    $sql = "SELECT m.missionid " .
        "FROM uscm_mission_tags AS m " .
        "INNER JOIN uscm_tags AS tg ON m.tagid = tg.id " .
        "INNER JOIN uscm_mission_names AS mn ON m.missionid = mn.id " .
        "WHERE m.tagid = :tagid";
    $sql = $sql . " ORDER BY mn.date DESC,mission_name_short DESC ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':tagid', $tagId, PDO::PARAM_INT);
    $stmt->execute();
    $missions = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $missions[] = $row['missionid'];
    }
    return $missions;
  }
}
