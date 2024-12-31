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

}
