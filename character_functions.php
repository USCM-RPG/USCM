<?php

/**
 *
 * @param integer $characterId
 * @param integer $platoonId
 * @return array
 */
function certificates($characterId, $platoonId) {
  $character = new Character($characterId);
  $character->setPlatoonId($platoonId);
  return $character->getCertificates();
}

/**
 * Get an array containing attributes for the character
 *
 * @param integer $characterId
 * @return Array <Characters>
 */
function characterAttributes($characterId) {
  $db = getDatabaseConnection();
  $sql = "SELECT attribute_id,attribute_name, value
                  FROM uscm_characters c
                  LEFT JOIN uscm_attributes a ON a.character_id=c.id
                  LEFT JOIN uscm_attribute_names an ON an.id=a.attribute_id
                  WHERE c.id=:cid ORDER BY attribute_name";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':cid', $characterId, PDO::PARAM_INT);
  $stmt->execute();
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $attributearray [$row ['attribute_id']] ['value'] = $row ['value'];
    $attributearray [$row ['attribute_id']] ['attribute_name'] = $row ['attribute_name'];
  }
  return $attributearray;
}

function getAttributesForCharacter($characterId) {
  return (new Character($characterId))->getAttributesForCharacter();
}

function getCertificateRequirements() {
  $db = getDatabaseConnection();
  $certreqsql = "SELECT certificate_id, req_item,value,value_greater,table_name,name
                FROM uscm_certificate_requirements cr
                LEFT JOIN uscm_certificate_names cn ON cn.id=cr.certificate_id";
  $cert = array ();
  $stmt = $db->prepare($certreqsql);
  $stmt->execute();
  /*
   * Array
   * (
   * [1] => Array //certificate id
   * (
   * [1] => Array //req_item id
   * (
   * [value] => 4
   * [value_greater] => 1
   * [table] => skill_names
   * )
   * )
   * }
   */
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $cert [$row ['certificate_id']] [$row ['req_item']] ['id'] = $row ['req_item'];
    $cert [$row ['certificate_id']] [$row ['req_item']] ['value'] = $row ['value'];
    $cert [$row ['certificate_id']] [$row ['req_item']] ['value_greater'] = $row ['value_greater'];
    $cert [$row ['certificate_id']] [$row ['req_item']] ['name'] = $row ['name'];
    $cert [$row ['certificate_id']] [$row ['req_item']] ['table_name'] = $row ['table_name'];
  }
  return $cert;
}

function getCommendationsForCharacter($characterId) {
  $db = getDatabaseConnection();
  $chosencertarray = array ();
  $commendationssql = "SELECT medal_short,medal_glory FROM uscm_characters c
                    LEFT JOIN uscm_missions as missions
                      ON missions.character_id = c.id
                    LEFT JOIN uscm_medal_names as mn
                      ON mn.id = missions.medal_id
                    WHERE character_id=:cid ORDER BY medal_glory DESC";
  $stmt = $db->prepare($commendationssql);
  $stmt->bindValue(':cid', $characterId, PDO::PARAM_INT);
  $stmt->execute();
  $commendations = $stmt->fetchAll(PDO::FETCH_ASSOC);

  return $commendations;
}

function getMissionTagsForCharacter($characterId) {
  $db = getDatabaseConnection();
  $tagarray = array ();
  $tagsql = "SELECT t.tag, COUNT(t.id) as tagcount FROM uscm_missions m JOIN uscm_mission_tags mt ON m.mission_id=mt.missionid JOIN uscm_tags t ON mt.tagid=t.id WHERE m.character_id=:cid GROUP BY t.id ORDER BY tagcount DESC";
  $stmt = $db->prepare($tagsql);
  $stmt->bindValue(':cid', $characterId, PDO::PARAM_INT);
  $stmt->execute();
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $tagarray [$row ['tag']] = $row ['tagcount'];
  }
  return $tagarray;
}

function getMissionTerrainForCharacter($characterId) {
  $db = getDatabaseConnection();
  $terrainarray = array ();
  $terrainsql = "SELECT expertise_name AS terrain, count(en.id) AS missions FROM uscm_missions m JOIN terrain_mission tm ON tm.mission_id=m.mission_id JOIN expertise_names en ON en.id=tm.expertise_id WHERE m.character_id=:cid GROUP BY en.id ORDER BY missions DESC";
  $stmt = $db->prepare($terrainsql);
  $stmt->bindValue(':cid', $characterId, PDO::PARAM_INT);
  $stmt->execute();
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $terrainarray [$row ['terrain']] = $row ['missions'];
  }
  return $terrainarray;
}

function getNumberOfMissionsForCharacter($characterId) {
  $db = getDatabaseConnection();
  $chosencertarray = array ();
  $missionssql = "SELECT count(id)+(SELECT extramissions FROM uscm_characters WHERE id=:cid) as missions FROM uscm_missions
                  WHERE character_id=:cid";
  $stmt = $db->prepare($missionssql);
  $stmt->bindValue(':cid', $characterId, PDO::PARAM_INT);
  $stmt->execute();
  $row = $stmt->fetch();
  $missions = $row ['missions'];
  return $missions;
}

function getSkillsForCharacter($characterId) {
  return (new Character($characterId))->getSkillsForCharacter();
}

function listCharacters($charactersql, $sortType) {
  // $sortType is either "alive", "dead", "retired" or "glory"
  $characters = array ();
  $medals = "";
  $glory = "";
  $dbReference = getDatabaseConnection();

  $stmt = $dbReference->query($charactersql);
  while ( $character = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $characters [sizeof($characters)] = $character;
  }

  foreach ( $characters as $key => $character ) {
    setMedalsAndGloryOnCharacter($characters, $key, $character);
  }
  // Obtain a list of columns
  $missions = array ();
  $rank = array ();
  $glory = array ();
  $medals = array ();
  foreach ( $characters as $key => $row ) {
    // var_dump($row);
    // echo "<br>";
    $rank [$key] = $row ['rank_id'];
    $missions [$key] = $row ['missions'];
    $glory [$key] = $row ['glory'];
    $medals [$key] = $row ['medals'];
  }

  // Sort the data with volume descending, edition ascending
  // Add $data as the last parameter, to sort by the common key
  if ($sortType == "alive") {
    array_multisort($rank, SORT_DESC, $missions, SORT_DESC, $characters);
  } elseif ($sortType == "dead" || $sortType == "retired") {
    array_multisort($missions, SORT_DESC, $rank, SORT_DESC, $glory, SORT_DESC, $characters);
  } elseif ($sortType == "glory") {
    array_multisort($glory, SORT_DESC, $missions, SORT_DESC, $rank, SORT_DESC, $characters);
  }
  return $characters;
}

function servedWith($characterid) {
	$characters = array ();
	$dbReference = getDatabaseConnection();
	$stmt = $dbReference->query("select concat(oc.forname, ' ', oc.lastname) as name,count(om.id) as missions, oc.status from uscm_missions as m join uscm_characters as c on m.character_id=c.id join uscm_missions as om on m.mission_id=om.mission_id and m.id!=om.id join uscm_characters as oc on om.character_id=oc.id where m.character_id=$characterid group by oc.id order by missions desc, status asc");

	while ( $character = $stmt->fetch(PDO::FETCH_ASSOC) ) {
		$characters [sizeof($characters)] = $character;
	}
	return $characters;
}

function setMedalsAndGloryOnCharacter(&$characters, $key, $character) {
  $medals = "";
  $glory = "";
  $characterId = $character ['cid'];
  $db = getDatabaseConnection();
  $sql = 'SELECT count(m.id)+(SELECT extramissions FROM uscm_characters WHERE id=:cid) as missions FROM uscm_missions m
        LEFT JOIN uscm_mission_names mn ON mn.id=m.mission_id
                WHERE character_id=:cid AND mn.date < NOW()';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':cid', $characterId, PDO::PARAM_INT);
  $stmt->execute();
  $missions = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $characters [$key] ['missions'] = $missions [0] ['missions'];

  $commendationssql = "SELECT medal_short,medal_glory FROM uscm_characters c
                LEFT JOIN uscm_missions as missions
                  ON missions.character_id = c.id
                LEFT JOIN uscm_medal_names as mn
                  ON mn.id = missions.medal_id
                WHERE character_id=:cid ORDER BY medal_glory DESC";
  $stmt = $db->prepare($commendationssql);
  $stmt->bindValue(':cid', $characterId, PDO::PARAM_INT);
  $stmt->execute();
  while ( $commendations = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    if ($commendations ['medal_short'] != "") {
      $medals = $medals . " " . $commendations ['medal_short'];
    }
    $glory = (int)$glory + $commendations ['medal_glory'];
  }
  $characters [$key] ['medals'] = ($medals != "") ? ($medals) : ("-");
  $characters [$key] ['glory'] = ($glory != "") ? ($glory) : ("0");
}

function traits($characterId) {
  return (new Character($characterId))->getTraits();
}

function lastMissionForCharacter($characterId) {
  $db = getDatabaseConnection();
  $sql = "SELECT DATE_FORMAT(date,'%Y-%m-%d') as date,mission_name_short, mission_id FROM uscm_mission_names LEFT JOIN uscm_missions as m on m.mission_id = uscm_mission_names.id WHERE character_id = :characterId ORDER BY date DESC LIMIT 1";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':characterId', $characterId, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetch();
}

?>
