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

public function getMissionsForTag($tagId) {
    $sql = "select missionid, uscm_mission_names.mission_name, uscm_mission_names.mission_name_short, uscm_platoon_names.name_short as platoonnameshort ".
      "from uscm_mission_tags ".
      "left join uscm_tags on uscm_mission_tags.tagid = uscm_tags.id ".
      "left join uscm_mission_names on uscm_mission_tags.missionid = uscm_mission_names.id ".
      "left join uscm_platoon_names on uscm_platoon_names.id = uscm_mission_names.platoon_id ".
      "where tagid = :tagid ".
      "order by uscm_mission_names.date desc, mission_name_short desc";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':tagid', $tagId, PDO::PARAM_INT);
    $missions = array();
    try {
      $stmt->execute();
      while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        $mission = new Mission();
        $mission->setId($row['missionid']);
        $mission->setName($row['mission_name']);
        $mission->setShortName($row['mission_name_short']);
        $mission->setPlatoonShortName($row['platoonnameshort']);
        $missions[] = $mission;
      }
    } catch (PDOException $e) {
      print "Error fetching missions by tag " . $e->getMessage() . "<br>";
    }
    return $missions;
  }
}
