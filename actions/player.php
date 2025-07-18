<?php
/*
 *	Functions handling players should go in here
 *
 */
require_once("../session.php");
include("../functions.php");
$userController = new UserController();
$playerController = new PlayerController();
$user = $userController->getCurrentUser();

$forname=$_POST['forname'];
$lastname=$_POST['lastname'];
$nickname=$_POST['nickname'];
$emailadress=$_POST['emailadress'];
$password=$_POST['password'];
$use_nickname=$_POST['use_nickname'];
$platoon_id=$_POST['platoon_id'];
$discordid=$_POST['discordid'];
$active=1;
if (!isset($_POST['active'])) {
	$active=0;
}

if ($_GET['what']=="create" && ($user->isAdmin() || $user->isGm())) {
  $player = new Player();
  $player->setGivenName($forname);
  $player->setSurname($lastname);
  $player->setNickname($nickname);
  $player->setEmailaddress($emailadress);
  $player->setPassword($password);
  $player->setUseNickname($use_nickname);
  $player->setPlatoonId($platoon_id);
  $player->setPlayerActive(1);
  $playerController->save($player);
}
elseif ($_GET['what']=="modify" && ($user->isAdmin() || $user->isGm() || $user->getId() == $_POST['id'])) {
  $player = $playerController->getPlayer($_POST['id']);
  if ($password) {
    $player->setPassword($password);
    $playerController->updatePassword($player);
  }
  $player->setGivenName($forname);
  $player->setSurname($lastname);
  $player->setNickname($nickname);
  $player->setEmailaddress($emailadress);
  $player->setPassword($password);
  $player->setUseNickname($use_nickname);
  $player->setPlatoonId($platoon_id);
  if(preg_match("/\d{17,18}/", $discordid)) {
    $player->setDiscordId($discordid);
  }
  $player->setPlayerActive($active);
  $playerController->update($player);
}

header("location:{$url_root}/index.php?url=player/edit.php");
?>
