<?php
$admin=($_SESSION['level']>=3)?(TRUE):(FALSE);
$gm=($_SESSION['level']>=2)?(TRUE):(FALSE);
$missionId = $_GET['id'];
$missionController = new MissionController();
$mission = $missionController->getMission($missionId);
$playerController = new PlayerController();
$gmUser = $playerController->getPlayer($mission->getGmId());
?>

<div class="mission">
<h1 class="heading heading-h1">
  Mission <?php echo $mission->getShortName();?>
  <span class="tag tag-<?php echo strtolower($mission->getPlatoonShortName());?>"><?php echo $mission->getPlatoonShortName();?></span>
</h1>

<h2 class="heading heading-h2">
  <?php echo $mission->getName();?>
  <span class="span">
    <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=names" class="colorfont">Change</a><?php } ?>
  </span>
</h2>

<dl class="list-description">
  <dt><a href="index.php?url=tags/list.php&mission=<?php echo $mission->getId();?>&what=tag" class="colorfont">Tags</a><?php</dt>
  <dd>
    <?php echo $mission->getTags();?><br>
    <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=tag" class="colorfont">Change</a><?php } ?>
  </dd>
  <dt>Date</dt>
  <dd>
    <?php echo $mission->getDate();?>
  </dd>
  <dt>GM</dt>
  <dd>
    <?php echo $gmUser->getName()?><br>
    <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=gm" class="colorfont">Change</a><?php } ?>
  </dd>
  <dt>Characters</dt>
  <dd>
    <?php
    $charactersAndPlayers = $missionController->getCharactersAndPlayers($mission);
    foreach ($charactersAndPlayers as $character) {
      echo $character['forname'] . " " . $character['lastname'] . " (" .  $character['pforname'] . " " . $character['plastname'] . ")<br>";
    } ?>
    <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=characters" class="colorfont">Change</a><?php } ?>
  </dd>
  <dt>Non-Player Characters</dt>
  <dd>
    <?php
    $charactersAndPlayers = $missionController->getNonPlayerCharacters($mission);
    foreach ($charactersAndPlayers as $character) {
      echo $character['forname'] . " " . $character['lastname'] . "<br>";
    } ?>
    <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=characters" class="colorfont">Change</a><?php } ?>
  </dd>
  <dt>Commendations</dt>
  <dd>
    <?php
    $commendations = $missionController->getCommendations($mission);
    foreach ($commendations as $commendation) {
      echo $commendation['forname'] . " " . $commendation['lastname'] . " - " .  $commendation['medal_short'] . "<br>";
    } ?>
    <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=commendations" class="colorfont">Change</a><?php } ?>
  </dd>
  <dt>Promotions</dt>
  <dd>
    <?php
    $promotions = $missionController->getPromotions($mission);
    foreach ($promotions as $promotion) {
      echo $promotion['forname'] . " " . $promotion['lastname'] . " - " .  $promotion['rank_short'] . "<br>";
    } ?>
    <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=promotions" class="colorfont">Change</a><?php } ?>
  </dd>
</dl>

<h3 class="heading heading-h3">
  Briefing
  <span class="span">
    <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=briefing" class="colorfont">Change</a><?php } ?>
  </span>
</h3>

<div>
  <?php echo $mission->getBriefing();?><br>
</div>

<h3 class="heading heading-h3">
  Debriefing
  <span class="span">
    <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=debriefing" class="colorfont">Change</a><?php } ?>
  </span>
</h3>

<div>
  <?php echo $mission->getDebriefing();?><br>
</div>
</div>
