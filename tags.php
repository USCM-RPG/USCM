<?php

/*
 * Functions handling tags should go in here
 *
 */
session_start();
require_once "functions.php";
$tagController = new TagController();
$missionController = new MissionController();
$db = getDatabaseConnection();
$userController = new UserController();
$user = $userController->getCurrentUser();

if ($user->isAdmin() || $user->isGm()) {
  /*
   * Updates a mission's tags
   *
   */
  $removeTags = array ();
  $addTags = array ();
  $oldTags = array ();
  $missionId = $_GET['mission'];

  //
  // Tags
  //
  // Finds all tags currently in database for mission
  $tags = $tagController->getTagsForMission($missionId);
  $mission = $missionController->getMission($missionId);
  foreach ( $tags as $tag ) {    
    $oldTags[$tag->getId()] = $tag->getId();
  }
  // walks through $_POST[] and decides what to delete, update and insert in database
  if ($_POST['tag'] == NULL)
    $_POST['tag'] = array ();
  foreach ( $_POST['tag'] as $tagId => $value) {
    if (array_key_exists($tagId, $oldTags)) {
      // remove the handled data from oldTags
      unset($oldTags[$tagId]);
    } elseif ($value != NULL) {
      $addTags[$tagId] = $tagId;
    }
  }
  // remove the tags that weren't in the $_POST
  foreach ( $oldTags as $tagId ) {
    $removeTags[$tagId] = $tagId;
    unset($oldTags[$tagId]);
  }
  foreach ( $removeTags as $tagId ) {
    $sql = "DELETE FROM uscm_mission_tags WHERE missionid=:m_id AND tagid=:t_id LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':m_id', $missionId, PDO::PARAM_INT);
    $stmt->bindValue(':t_id', $tagId, PDO::PARAM_INT);
    $stmt->execute();
  }
  foreach ( $addTags as $tagId ) {
    $sql = "INSERT INTO uscm_mission_tags SET missionid=:m_id,tagid=:t_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':m_id', $missionId, PDO::PARAM_INT);
    $stmt->bindValue(':t_id', $tagId, PDO::PARAM_INT);
    $stmt->execute();
  }

  header("location:{$url_root}/index.php?url=missions/details.php&id={$_GET['mission']}");
}
