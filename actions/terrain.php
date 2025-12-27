<?php

require_once("../session.php");
require_once "../functions.php";

$missionController = new MissionController();
$db = getDatabaseConnection();
$userController = new UserController();
$user = $userController->getCurrentUser();

if ($user->isAdmin() || $user->isGm()) {
  /*
   * Updates a mission's terrain
   *
   */
  $removeTerrains = array ();
  $addTerrains = array ();
  $oldTerrains = array ();
  $missionId = $_GET['mission'];
  $mission = $missionController->getMission($missionId);

  //
  // Terrains
  //
  // Finds all terrains currently in database for mission
  $terrains = $missionController->getTerrain($mission);

  foreach ( $terrains as $terrain ) {
    $oldTerrains[$terrain->getId()] = $terrain->getId();
  }
  // walks through $_POST[] and decides what to delete, update and insert in database
  if ($_POST['terrain'] == NULL)
    $_POST['terrain'] = array ();
  foreach ( $_POST['terrain'] as $terrainId => $value) {
    if (array_key_exists($terrainId, $oldTerrains)) {
      // remove the handled data from oldTags
      unset($oldTerrains[$terrainId]);
    } elseif ($value != NULL) {
      $addTerrains[$terrainId] = $terrainId;
    }
  }
  // remove the terrains that weren't in the $_POST
  foreach ( $oldTerrains as $terrainId ) {
    $removeTerrains[$terrainId] = $terrainId;
    unset($oldTerrains[$terrainId]);
  }
  foreach ( $removeTerrains as $terrainId ) {
    $sql = "DELETE FROM terrain_mission WHERE mission_id=:m_id AND expertise_id=:e_id LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':m_id', $missionId, PDO::PARAM_INT);
    $stmt->bindValue(':e_id', $terrainId, PDO::PARAM_INT);
    $stmt->execute();
  }
  foreach ( $addTerrains as $terrainId ) {
    $sql = "INSERT INTO terrain_mission SET mission_id=:m_id,expertise_id=:e_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':m_id', $missionId, PDO::PARAM_INT);
    $stmt->bindValue(':e_id', $terrainId, PDO::PARAM_INT);
    $stmt->execute();
  }

  header("location:{$url_root}/index.php?url=missions/details.php&id={$_GET['mission']}");
}
