<?php
Class CharacterController {
  private $db = NULL;

  function __construct() {
    $this->db = getDatabaseConnection();
  }

  /**
   *
   * @param int $characterId
   * @return void|Character
   */
  public function getCharacter($characterId) {
    if ($characterId == null || $characterId == 0) {
      return;
    }
    $character = new Character();
    $sql = "SELECT userid, platoon_id, forname, lastname, Enlisted, Age, Gender, UnusedXP,
        AwarenessPoints, CoolPoints, ExhaustionPoints, FearPoints, LeadershipPoints, PsychoPoints,
        TraumaPoints, MentalPoints, status, status_desc, specialty_name, uscm_specialty_names.id as specialty_id,
        rank_id, rank_short, rank_long, rank_desc, encalien, encgrey, encpred, encai, encarach, extramissions, extrasims, version
        FROM uscm_characters
        LEFT JOIN uscm_ranks ON uscm_characters.id = uscm_ranks.character_id
        LEFT JOIN uscm_rank_names ON  uscm_ranks.rank_id = uscm_rank_names.id
        LEFT JOIN uscm_specialty ON uscm_characters.id = uscm_specialty.character_id
        LEFT JOIN uscm_specialty_names ON  uscm_specialty.specialty_name_id = uscm_specialty_names.id
        WHERE uscm_characters.id = :cid LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':cid', $characterId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row != null) {
      $platoonId = $row['platoon_id'];
      $playerId = $row['userid'];
      $character->setId($characterId);
      $character->setGivenName($row['forname']);
      $character->setSurname($row['lastname']);
      $character->setPlayerId($playerId);
      $character->setPlatoonId($platoonId);
      $character->setEnlistedDate($row['Enlisted']);
      $character->setAge($row['Age']);
      $character->setGender($row['Gender']);
      $character->setUnusedXp($row['UnusedXP']);
      $character->setAwarenessPoints($row['AwarenessPoints']);
      $character->setCoolPoints($row['CoolPoints']);
      $character->setExhaustionPoints($row['ExhaustionPoints']);
      $character->setFearPoints($row['FearPoints']);
      $character->setLeadershipPoints($row['LeadershipPoints']);
      $character->setPsychoPoints($row['PsychoPoints']);
      $character->setTraumaPoints($row['TraumaPoints']);
      $character->setMentalPoints($row['MentalPoints']);
      $character->setStatus($row['status']);
      $character->setStatusDescription($row['status_desc']);
      $character->setRankShort($row['rank_short']);
      $character->setRankLong($row['rank_long']);
      $character->setRankDescription($row['rank_desc']);
      $character->setRankId($row['rank_id']);
      $character->setSpecialtyName($row['specialty_name']);
      $character->setSpecialtyId($row['specialty_id']);
	  $character->setEncounterAlien($row['encalien']);
	  $character->setEncounterGrey($row['encgrey']);
	  $character->setEncounterPredator($row['encpred']);
	  $character->setEncounterAI($row['encai']);
	  $character->setEncounterArachnid($row['encarach']);
	  $character->setExtraMissions($row['extramissions']);
	  $character->setExtraSims($row['extrasims']);
	  $character->setVersion($row['version']);
      $platoonController = new PlatoonController();
      $playerController = new PlayerController();
      $medalController = new MedalController();
      $thisController = $this;
      $character->setPlatoon(function () use ($platoonController, $platoonId) {
        return $platoonController->getPlatoon($platoonId);
      });
      $character->setPlayer(function () use ($playerController, $playerId) {
        return $playerController->getPlayer($playerId);
      });
      $character->setMedals(function () use ($medalController, $characterId) {
        return $medalController->getMedalsForCharacter($characterId);
      });
      $character->setAdvantagesVisible(function () use ($thisController, $characterId) {
        return $thisController->getCharactersVisibleAdvantages($characterId);
      });
      $character->setAdvantagesAll(function () use ($thisController, $characterId) {
        return $thisController->getCharactersAllAdvantages($characterId);
      });
      $character->setDisadvantagesVisible(function () use ($thisController, $characterId) {
        return $thisController->getCharactersVisibleDisadvantages($characterId);
      });
      $character->setDisadvantagesAll(function () use ($thisController, $characterId) {
        return $thisController->getCharactersAllDisadvantages($characterId);
      });
      $character->setPsychoDisadvantagesAll(function () use ($thisController, $characterId) {
        return $thisController->getCharacterPsychoDisadv($characterId);
      });
    }
    return $character;
  }

    /**
   *
   * @return Character[]
   */
  public function getUserActiveCharacters($userId, $includepow=FALSE) {
    if ($userId == NULL) {
      return;
    }
    $characters = array();
    $sql = "SELECT c.id
              FROM {$_SESSION['table_prefix']}characters c
              WHERE c.userid=:uid AND c.status='Active'";
    if ($includepow) {
		$sql = $sql . " OR c.status='PoW'";
	}
	$sql = $sql . "ORDER BY c.lastname,c.forname";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
    $stmt->execute();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $character = $this->getCharacter($row['id']);
      $characters[] = $character;
    }
    return $characters;
  }

    /**
   *
   * @return Character[]
   */
  public function getActiveCharacters() {
    $characters = array();
    $sql = "SELECT c.id
              FROM {$_SESSION['table_prefix']}characters c
              WHERE c.status!='Dead' AND c.status!='Retired'
              ORDER BY c.lastname,c.forname";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $character = $this->getCharacter($row['id']);
      $characters[] = $character;
    }
    return $characters;
  }

    /**
   *
   * @param Mission $mission
   * @return Character[]
   */
  public function getAvailableCharactersForMission($mission) {
    $characters = array();
    $sql = "SELECT c.id
              FROM uscm_characters c LEFT JOIN uscm_missions m ON c.id=m.character_id AND m.mission_id=:missionId
              WHERE m.mission_id=:missionId OR (c.status!='Dead' AND c.status!='Retired')
              ORDER BY c.lastname,c.forname;";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':missionId', $mission->getId(), PDO::PARAM_INT);
    $stmt->execute();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $character = $this->getCharacter($row['id']);
      $characters[] = $character;
    }
    return $characters;
  }

  /**
   *
   * @param Mission $mission
   * @return Character[]
   */
  public function getCharacterIdsOnMission($mission) {
    $withOnMission = array();
    $sql = "SELECT character_id FROM {$_SESSION['table_prefix']}missions m WHERE mission_id=:missionId";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':missionId', $mission->getId(), PDO::PARAM_INT);
    $stmt->execute();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $withOnMission[$row['character_id']] = TRUE;
    }
    return $withOnMission;
  }

  /**
   *
   * @param Mission $mission
   * @return Character[]
   */
  public function getCharactersOnMission($mission) {
    $withOnMission = array();
    $sql = "SELECT character_id FROM {$_SESSION['table_prefix']}missions m WHERE mission_id=:missionId";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':missionId', $mission->getId(), PDO::PARAM_INT);
    $stmt->execute();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $character = $this->getCharacter($row['character_id']);
      $withOnMission[] = $character;
    }
    return $withOnMission;
  }

  /**
   *
   * @return Specialty[]
   */
  public function getSpecialties() {
    $specialties = array ();
    $sql = "SELECT id, specialty_name FROM uscm_specialty_names ORDER BY specialty_name";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $specialty = new Specialty();
      $specialty->setId($row['id']);
      $specialty->setName($row['specialty_name']);
      $specialties[] = $specialty;
    }
    return $specialties;
  }

  /**
   *
   * @return CharacterAttribute[]
   */
  public function getAttributes() {
    $attributesql = "SELECT id, attribute_name FROM uscm_attribute_names ORDER BY id";
    $stmt = $this->db->prepare($attributesql);
    $stmt->execute();
    $attributes = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $attribute = new CharacterAttribute();
      $attribute->setId($row['id']);
      $attribute->setName($row['attribute_name']);
      $attributes[] = $attribute;
    }
    return $attributes;
  }

  /**
   *
   * @param Character $character
   * @return CharacterAttribute[]
   */
  function getAttributesForCharacter($character) {
    $db = getDatabaseConnection();
    $attribsql = "SELECT attribute_id as id,value
            FROM uscm_attributes
            WHERE character_id=:cid";
    $stmt = $db->prepare($attribsql);
    $stmt->bindValue(':cid', $character->getId(), PDO::PARAM_INT);
    $stmt->execute();
    $attributes = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $attribute = new CharacterAttribute();
      $attribute->setId($row['id']);
      $attribute->setName($row['attribute_name']);
      $attributes[] = $attribute;
    }
    return $attributes;
  }

  /**
   *
   * @return Skill[]
   */
  public function getSkills() {
    $sql = "SELECT id, skill_name, optional, skill_group_id, default_value, description
              FROM uscm_skill_names";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $skills = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $skill = new Skill();
      $skill->setId($row['id']);
      $skill->setName($row['skill_name']);
      $skill->setOptional($row['optional']);
      $skill->setDefaultValue($row['default_value']);
      $skill->setDescription($row['description']);
      $skill->setSkillGroupId($row['skill_group_id']);
      $skills[] = $skill;
    }
    return $skills;
  }

  /**
   *
   * @return Skill[]
   */
  function getSkillsGrouped($minversion=2) {
    $sql = "SELECT sn.id, skill_name, optional, skill_group_id, default_value, description, skill_group_name
                FROM uscm_skill_names sn
                LEFT JOIN uscm_skill_groups sg on sn.skill_group_id=sg.id
                WHERE sn.version >= :version
                ORDER BY sg.id,sn.skill_name";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':version', $minversion, PDO::PARAM_INT);
    $stmt->execute();
    $skills = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $skill = new Skill();
      $skill->setId($row['id']);
      $skill->setName($row['skill_name']);
      $skill->setOptional($row['optional']);
      $skill->setDefaultValue($row['default_value']);
      $skill->setDescription($row['description']);
      $skill->setSkillGroupId($row['skill_group_id']);
      $skill->setSkillGroupName($row['skill_group_name']);
      $skills[] = $skill;
    }
    return $skills;
  }

  /**
   *
   * @param Character $character
   * @return Skill[]
   */
  function getSkillsForCharacter($character) {
    $db = getDatabaseConnection();
    $skillsql = "SELECT skill_name_id as id,value
            FROM uscm_skills
            WHERE character_id=:cid";
    $stmt = $db->prepare($skillsql);
    $stmt->bindValue(':cid', $character->getId(), PDO::PARAM_INT);
    $stmt->execute();
    $skills = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $skill = new Skill();
      $skill->setId($row['id']);
      $skill->setName($row['skill_name']);
      $skill->setOptional($row['optional']);
      $skill->setDefaultValue($row['default_value']);
      $skill->setDescription($row['description']);
      $skill->setSkillGroupId($row['skill_group_id']);
      $skills[] = $skill;
    }
    return $skills;
  }

    /**
   * Get all expertise for character
   * @param Character $character
   * @return Expertise[]
   */
  function getExpertise($character) {
	  $expertisearray = array();
	      if ($character->getVersion() < 3) {
		return $expertisearray;
	}
	$expertisesql = "SELECT en.id,expertise_name, expertise_group_id, value FROM expertise_names en
              JOIN expertises e ON e.expertise_id=en.id
              JOIN expertise_groups eg ON en.expertise_group_id=eg.id
              WHERE e.character_id=:cid ORDER BY en.expertise_name";
	$db = getDatabaseConnection();
	$stmt = $db->prepare($expertisesql);
    $stmt->bindValue(':cid', $character->getId(), PDO::PARAM_INT);
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

    /**
   * Get expertise linked to skill for character
   * @param Character $character
   * @param int $skillid
   * @return Expertise[]
   */
  function getExpertiseOnSkill($character, $skillid) {
	  $expertisearray = array();
	      if ($character->getVersion() < 3) {
		return $expertisearray;
	}
	$expertisesql = "SELECT en.id,expertise_name, expertise_group_id, value FROM expertises e
JOIN expertise_skill es on e.expertise_id=es.expertiseid
JOIN expertise_names en ON e.expertise_id=en.id
WHERE e.character_id=:cid AND es.skillid=:sid";
	$db = getDatabaseConnection();
	$stmt = $db->prepare($expertisesql);
    $stmt->bindValue(':cid', $character->getId(), PDO::PARAM_INT);
    $stmt->bindValue(':sid', $skillid, PDO::PARAM_INT);
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

    /**
   * Get only expertise not linked to skill for character
   * @param Character $character
   * @return Expertise[]
   */
  function getExpertiseNotOnSkills($character) {
	  $expertisearray = array();
	      if ($character->getVersion() < 3) {
		return $expertisearray;
	}
	$expertisesql = "SELECT en.id,expertise_name, expertise_group_id, value FROM expertise_names en
              LEFT JOIN expertise_skill es on en.id=es.expertiseid
              JOIN expertises e ON e.expertise_id=en.id
              JOIN expertise_groups eg ON en.expertise_group_id=eg.id
              WHERE e.character_id=:cid AND es.expertiseid IS NULL ORDER BY expertise_group_id, en.expertise_name";
	$db = getDatabaseConnection();
	$stmt = $db->prepare($expertisesql);
    $stmt->bindValue(':cid', $character->getId(), PDO::PARAM_INT);
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

  /**
   *
   * @return CharacterTrait[]
   */
  function getTraits() {
    $sql = "SELECT tn.id,trait_name, description FROM uscm_trait_names tn ORDER BY tn.trait_name";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $traits = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $trait = new CharacterTrait();
      $trait->setId($row['id']);
      $trait->setName($row['trait_name']);
      $trait->setDescription($row['description']);
      $traits[] = $trait;
    }
    return $traits;
  }

  /**
   *
   * @return Advantage[]
   */
  function getAdvantages() {
    $sql = "SELECT id, advantage_name, value, description, visible
              FROM uscm_advantage_names ORDER BY advantage_name";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $advantages = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $advantage = new Advantage();
      $advantage->setId($row['id']);
      $advantage->setName($row['advantage_name']);
      $advantage->setDescription($row['description']);
      $advantage->setValue($row['value']);
      $advantage->setVisible($row['visible']);
      $advantages[] = $advantage;
    }
    return $advantages;
  }

  /**
   *
   * @param int $characterId Id of a Character
   * @return Advantage[]
   */
  function getCharactersVisibleAdvantages($characterId) {
    return $this->getCharactersAdvantages($characterId, TRUE);
  }

  /**
   *
   * @param int $characterId Id of a Character
   * @return Advantage[]
   */
  function getCharactersAllAdvantages($characterId) {
    return $this->getCharactersAdvantages($characterId, FALSE);
  }

  /**
   * @param int $characterId Id of a Character
   * @param boolean $onlyvisible If only publicly visible advantages should be returned
   * @return Advantage[]
   */
  private function getCharactersAdvantages($characterId, $onlyvisible) {
    $visible = $onlyvisible ? " AND an.visible = 1" : "";
    $sql = "SELECT an.id, advantage_name, value, description, visible, a.id as uid
            FROM uscm_advantage_names an
            LEFT JOIN uscm_advantages a ON a.advantage_name_id=an.id
            LEFT JOIN uscm_characters c ON c.id=a.character_id
            WHERE a.character_id=:cid " . $visible . " ORDER BY advantage_name";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':cid', $characterId, PDO::PARAM_INT);
    $stmt->execute();
    $advantages = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $advantage = new Advantage();
      $advantage->setId($row['id']);
      $advantage->setName($row['advantage_name']);
      $advantage->setDescription($row['description']);
      $advantage->setValue($row['value']);
      $advantage->setVisible($row['visible']);
      $advantages[$row['uid']] = $advantage;
    }
    return $advantages;
  }

  /**
   *
   * @return Disadvantage[]
   */
  function getDisadvantages() {
    $sql = "SELECT id, disadvantage_name, value, description, visible
              FROM uscm_disadvantage_names ORDER BY disadvantage_name";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $disadvantages = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $disadvantage = new Disadvantage();
      $disadvantage->setId($row['id']);
      $disadvantage->setName($row['disadvantage_name']);
      $disadvantage->setDescription($row['description']);
      $disadvantage->setValue($row['value']);
      $disadvantage->setVisible($row['visible']);
      $disadvantages[] = $disadvantage;
    }
    return $disadvantages;
  }

  /**
   * @param int $characterId Id of a Character
   * @return Disadvantage[]
   */
  function getCharactersVisibleDisadvantages($characterId) {
    return $this->getCharactersDisadvantages($characterId, TRUE);
  }

  /**
   * @param int $characterId Id of a Character
   * @return Disadvantage[]
   */
  function getCharactersAllDisadvantages($characterId) {
    return $this->getCharactersDisadvantages($characterId, FALSE);
  }

    /**
   *
   * @param int $characterId Id of a Character
   * @param boolean $onlyvisible If only publicly visible disadvantages should be returned
   * @return Disadvantage[]
   */
  private function getCharactersDisadvantages($characterId, $onlyvisible) {
    $disadvarray = array ();
    $sql = "SELECT dn.id, disadvantage_name, value, description, visible, d.id as uid
            FROM uscm_disadvantage_names dn
            LEFT JOIN uscm_disadvantages d ON d.disadvantage_name_id=dn.id
            LEFT JOIN uscm_characters c ON c.id=d.character_id
            WHERE d.character_id=:cid ORDER BY disadvantage_name";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':cid', $characterId, PDO::PARAM_INT);
    $stmt->execute();
    $disadvantages = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $disadvantage = new Disadvantage();
      $disadvantage->setId($row['id']);
      $disadvantage->setName($row['disadvantage_name']);
      $disadvantage->setDescription($row['description']);
      $disadvantage->setValue($row['value']);
      $disadvantage->setVisible($row['visible']);
      $disadvantages[$row['uid']] = $disadvantage;
    }
    return $disadvantages;
  }
  
  function getCharacterAllPsychoDisadvantages($characterId) {
    return $this->getCharacterPsychoDisadv($characterId);
  }

  private function getCharacterPsychoDisadv($characterId) {
    $disadvarray = array ();
    $sql = "SELECT psychodis_id as pdid, name, value, p.id as uid FROM psychodisadvantages p 
JOIN psychodis_names pn ON p.psychodis_id=pn.id
WHERE p.character_id=:cid";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':cid', $characterId, PDO::PARAM_INT);
    $stmt->execute();
    $disadvantages = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $disadvantage = new PsychoDisadvantage();
      $disadvantage->setId($row['pdid']);
      $disadvantage->setName($row['name']);
      //$disadvantage->setDescription($row['description']);
      $disadvantage->setValue($row['value']);
      $disadvantages[$row['uid']] = $disadvantage;
    }
    return $disadvantages;
  }

  /**
   *
   * @return Certificate[]
   */
  function getCertificates() {
    $sql = "SELECT id,name, description FROM uscm_certificate_names ORDER BY name";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $certificates = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $certificate = new Certificate();
      $certificate->setId($row['id']);
      $certificate->setName($row['name']);
      $certificate->setDescription($row['description']);
      $certificates[] = $certificate;
    }
    return $certificates;
  }

  /**
   *
   * @param Character $character
   * @return Certificate[] All certificates that the character has
   */
  function getAllCertificatesForCharacter($character) {
    $certificatesForCharacter = array ();
    if ($character->getVersion() > 2) {
		return $certificatesForCharacter;
	}
    $platoon = $character->getPlatoon();
    $aquiredCertificates = $this->getAquiredCertificatesForCharacter($character);
    $platoonCertificates = $platoon->getCertificates();
    $characterSkills = $this->getSkillsForCharacter($character);
    $characterAttributes = $this->getAttributesForCharacter($character);

    $characterSkillsAndAttributes = array ();
    foreach ( $characterSkills as $id => $value ) {
      $characterSkillsAndAttributes ['skill_names'] [$id] = $value;
    }
    foreach ( $characterAttributes as $id => $value ) {
      $characterSkillsAndAttributes ['attribute_names'] [$id] = $value;
    }

    $cert = getCertificateRequirements();
    foreach ($cert as $id => $requirements) {
      $requirementsMet = FALSE;
      if (in_array($id, $platoonCertificates) || in_array($id, $aquiredCertificates)) {
        $hasRequirement = FALSE;
        foreach ($requirements as $requirement) {
          $hasRequirement = $this->hasCharacterMetRequirement($requirement, $characterSkillsAndAttributes);
          if (!$hasRequirement) {
            break;
          }
        }
        $requirementsMet = $hasRequirement;
      }

      if ($requirementsMet) {
        $certificatesForCharacter[$id]['id'] = $id;
        reset($requirements);
        $name = current($requirements);
        $certificatesForCharacter[$id]['name'] = $name['name'];
      }
    }

    return $certificatesForCharacter;
  }

  private function hasCharacterMetRequirement($requirement, $characterSkillsAndAttributes) {
    $hasRequirement = FALSE;
    if ($this->shouldHaveValueGreaterThanRequirement($requirement)) {
      if (array_key_exists($requirement['id'], $characterSkillsAndAttributes[$requirement['table_name']]) &&
           $characterSkillsAndAttributes[$requirement['table_name']][$requirement['id']] >= $requirement['value']) {
        $hasRequirement = TRUE;
      }
    } else {
      if ($characterSkillsAndAttributes[$requirement['table_name']][$requirement['id']] <= $requirement['value']) {
        $hasRequirement = TRUE;
      }
    }

    return $hasRequirement;
  }

  private function shouldHaveValueGreaterThanRequirement() {
    if ($requirement['value_greater'] == "1") {
      return TRUE;
    }
    return FALSE;
  }

  function getAquiredCertificatesForCharacter($character) {
    $characterCertificates = array();

    return $characterCertificates;
  }

  public function getCharactersByUser($userId) {
    $sql = "select uscm_characters.id as character_id, forname, lastname, DATE_FORMAT(enlisted,'%Y-%m-%d') as enlisted, status, uscm_rank_names.rank_short, uscm_specialty_names.specialty_name
from uscm_characters
left join uscm_ranks on uscm_ranks.character_id = uscm_characters.id
left join uscm_rank_names on uscm_rank_names.id = uscm_ranks.rank_id
left join uscm_specialty on uscm_specialty.character_id = uscm_characters.id
left join uscm_specialty_names on uscm_specialty_names.id = uscm_specialty.specialty_name_id
where userid = :userid
order by enlisted desc";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':userid', $userId, PDO::PARAM_INT);
    $characters = array();
    try {
      $stmt->execute();
      while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        $character = new Character($row['character_id']);
        $character->setGivenName($row['forname']);
        $character->setSurname($row['lastname']);
        $character->setStatus($row['status']);
        $character->setEnlistedDate($row['enlisted']);
        $character->setRankShort($row['rank_short']);
        $character->setSpecialtyName($row['specialty_name']);
        $characters[] =  $character;
      }
    } catch (PDOException $e) {
      print "Error fetching characters by player " . $e->getMessage() . "<br>";
    }
    return $characters;
  }
}
