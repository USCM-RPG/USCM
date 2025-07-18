<?php
Class PlayerController {
  private $db = NULL;

  function __construct() {
    $this->db = getDatabaseConnection();
  }

  /**
   *
   * @param Player $player
   */
  public function save($player) {
    $sql="INSERT INTO Users SET forname=:givenName,nickname=:nickname,lastname=:surname,
           emailadress=:emailadress,`password`=PASSWORD(:password),use_nickname=:useNickname
           ,platoon_id=:platoonId";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':givenName', $player->getGivenName(), PDO::PARAM_STR);
    $stmt->bindValue(':nickname',  $player->getNickname(), PDO::PARAM_STR);
    $stmt->bindValue(':surname',  $player->getSurname(), PDO::PARAM_STR);
    $stmt->bindValue(':emailadress',  $player->getEmailaddress(), PDO::PARAM_STR);
    $stmt->bindValue(':password',  $player->getPassword(), PDO::PARAM_STR);
    $stmt->bindValue(':useNickname',  $player->getUseNickname(), PDO::PARAM_INT);
    $stmt->bindValue(':platoonId',  $player->getPlatoonId(), PDO::PARAM_INT);
    try {
      $this->db->beginTransaction();
      $stmt->execute();
      $this->db->commit();
    } catch (PDOException $e) {
      $this->db->rollBack();
    }
  }

  /**
   *
   * @param Player $player
   */
  public function update($player) {
    $sql="UPDATE Users SET forname=:givenName,nickname=:nickname,lastname=:surname,
           emailadress=:emailadress,use_nickname=:useNickname
           ,platoon_id=:platoonId,discordid=:discordId,active=:playeractive WHERE id = :playerId";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':playerId', $player->getId(), PDO::PARAM_INT);
    $stmt->bindValue(':givenName', $player->getGivenName(), PDO::PARAM_STR);
    $stmt->bindValue(':nickname',  $player->getNickname(), PDO::PARAM_STR);
    $stmt->bindValue(':surname',  $player->getSurname(), PDO::PARAM_STR);
    $stmt->bindValue(':emailadress',  $player->getEmailaddress(), PDO::PARAM_STR);
    $stmt->bindValue(':useNickname',  $player->getUseNickname(), PDO::PARAM_INT);
    $stmt->bindValue(':platoonId',  $player->getPlatoonId(), PDO::PARAM_INT);
	$stmt->bindValue(':playeractive',  $player->getPlayerActive(), PDO::PARAM_INT);
	$stmt->bindValue(':discordId',  $player->getDiscordId(), PDO::PARAM_STR);
    try {
      $this->db->beginTransaction();
      $stmt->execute();
      $this->db->commit();
    } catch (PDOException $e) {
      $this->db->rollBack();
    }
  }

  public function updatePassword($player) {
    $sql="UPDATE Users SET `password`=PASSWORD(:password) WHERE id = :playerId";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':playerId', $player->getId(), PDO::PARAM_INT);
    $stmt->bindValue(':password',  $player->getPassword(), PDO::PARAM_STR);
    try {
      $this->db->beginTransaction();
      $stmt->execute();
      $this->db->commit();
    } catch (PDOException $e) {
      $this->db->rollBack();
    }
  }

  public function getPlayer($playerId) {
    $player = new Player();
    if ($playerId == NULL) {
      return $player;
    }
    $playersql = "SELECT Users.id, forname, nickname, lastname, emailadress, use_nickname, platoon_id,
        logintime, lastlogintime, GMs.userid as gm, GMs.RPG_id as RPG_id, GMs.active as gmactive, Users.active as playeractive, ".
        "Admins.userid as admin, discordid FROM Users " .
        "LEFT JOIN GMs on GMs.userid = Users.id " .
        "LEFT JOIN Admins on Admins.userid = Users.id WHERE Users.id = :userid LIMIT 1";
    $stmt = $this->db->prepare($playersql);
    $stmt->bindValue(':userid', $playerId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row != null) {
      $player = $this->assignPlayerData($row);
    }
    return $player;
  }

  /**
   *
   * @return Player[]
   */
  public function getActivePlayers() {
    $playersql = "SELECT Users.id, forname, nickname, lastname, emailadress, use_nickname, platoon_id, logintime, lastlogintime, GMs.userid as gm, GMs.RPG_id, GMs.active as gmactive, Users.active as playeractive, Admins.userid as admin FROM Users LEFT JOIN GMs on GMs.userid = Users.id LEFT JOIN Admins on Admins.userid = Users.id WHERE Users.active=1 ORDER BY platoon_id, forname, lastname";
    $stmt = $this->db->prepare($playersql);
    $stmt->execute();
    $playerList = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $playerList[] = $this->assignPlayerData($row);
    }
    return $playerList;
  }

  public function getAllPlayers() {
    $playersql = "SELECT Users.id, forname, nickname, lastname, emailadress, use_nickname, platoon_id,
        logintime, lastlogintime, GMs.userid as gm, GMs.RPG_id, GMs.active as gmactive, Users.active as playeractive, ".
        "Admins.userid as admin FROM Users " .
        "LEFT JOIN GMs on GMs.userid = Users.id " .
        "LEFT JOIN Admins on Admins.userid = Users.id ORDER BY forname, lastname";
    $stmt = $this->db->prepare($playersql);
    $stmt->execute();
    $playerList = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $playerList[] = $this->assignPlayerData($row);
    }
    return $playerList;
  }

  private function assignPlayerData($data) {
    $player = new Player();
    $player->setId($data ['id']);
    $player->setGivenName($data ['forname']);
    $player->setNickname($data ['nickname']);
    $player->setSurname($data ['lastname']);
    $player->setEmailaddress($data ['emailadress']);
    $player->setUseNickname($data ['use_nickname']);
    $player->setPlatoonId($data ['platoon_id']);
    $player->setLoginTime($data ['logintime']);
    $player->setLastLoginTime($data ['lastlogintime']);
    if ($data['gm']) {
      $player->setGm(TRUE);
    } else {
      $player->setGm(FALSE);
    }
    $player->setGmRpgId($data['RPG_id']);
    $player->setGmActive($data['gmactive']);
	  $player->setPlayerActive($data['playeractive']);
	  $player->setDiscordId($data['discordid'] ?? NULL);
    if ($data['admin']) {
      $player->setAdmin(TRUE);
    } else {
      $player->setAdmin(FALSE);
    }
    return $player;
  }

  public function getActivePlayersInPlatoon($platoonId) {
    $playersql = "SELECT Users.id,forname,lastname,name_short FROM Users
                  LEFT JOIN uscm_platoon_names pn ON pn.id=Users.platoon_id
                  WHERE platoon_id=:platoonid AND Users.active=1
                  ORDER BY platoon_id,forname,lastname";
    $stmt = $this->db->prepare($playersql);
    $stmt->bindValue(':platoonid', $platoonId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getPlayersInPlatoon($platoonId) {
    $playersql = "SELECT Users.id,forname,lastname,name_short FROM Users
                  LEFT JOIN uscm_platoon_names pn ON pn.id=Users.platoon_id
                  WHERE platoon_id=:platoonid
                  ORDER BY platoon_id,forname,lastname";
    $stmt = $this->db->prepare($playersql);
    $stmt->bindValue(':platoonid', $platoonId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getGms() {
    $gms = array();
    $gmsql = "SELECT Users.id,forname,lastname FROM Users LEFT JOIN GMs on GMs.userid=Users.id
                LEFT JOIN RPG on RPG.id=GMs.rpg_id
                WHERE GMs.active=1";
    $stmt = $this->db->prepare($gmsql);
    $stmt->execute();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      $gm = $this->getPlayer($row['id']);
      $gms[] = $gm;
    }
    return $gms;
  }
}
