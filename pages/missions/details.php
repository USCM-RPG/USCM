<?php
$admin=($_SESSION['level']>=3)?(TRUE):(FALSE);
$gm=($_SESSION['level']>=2)?(TRUE):(FALSE);
$missionId = $_GET['id'];
$missionController = new MissionController();
$mission = $missionController->getMission($missionId);
$tags = $missionController->getTagsForMission($missionId);
$playerController = new PlayerController();
$gmUser = $playerController->getPlayer($mission->getGmId());
$expertiseController = new ExpertiseController();
$terrain = $expertiseController->getMissionTerrain($missionId);
?>

<div class="mission">
<h1 class="heading heading-h1">
  Mission <span style="view-transition-name: transition-mission-<?php echo $mission->getId();?>;"><?php echo $mission->getShortName();?></span>
  <span class="tag tag-<?php echo strtolower($mission->getPlatoonShortName());?>" style="view-transition-name: transition-mission-platoon-<?php echo $mission->getId();?>;"><?php echo $mission->getPlatoonShortName();?></span>
</h1>

<h2 class="heading heading-h2">
  <span style="view-transition-name: transition-mission-name-<?php echo $mission->getId();?>;">
    <?php echo $mission->getName();?>
  </span>
  <span class="span">
    <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=names" class="colorfont">Change</a><?php } ?>
  </span>
</h2>

<dl class="list-description">
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
  <?php
  $npcs = $missionController->getNonPlayerCharacters($mission);
  if ($npcs) { ?>
  <dt>Non-Player Characters</dt>
  <dd>
    <?php
    foreach ($npcs as $character) {
      echo $character['forname'] . " " . $character['lastname'] . "<br>";
    } ?>
    <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=characters" class="colorfont">Change</a><?php } ?>
  </dd>
  <?php } ?>
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
  <dt>
    Tags
  </dt>
  <dd>
    <div>
      <?php foreach ($tags as $tag) {
        echo '<a href="index.php?url=missions/list.php&tag='. $tag->getId() .'">'. $tag->getName() .'</a> ';
      } ?>
    </div>
    <?php if (!empty($tags)) { echo '<br>'; } ?>
    <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=tag" class="colorfont">Change</a><?php } ?>
  </dd>

  <!--stuff to show terrain in missions starts here-->
  <dt>Terrain</dt>
<dd>
 <?php echo $terrain;?><br>
 <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=terrain" class="colorfont">Change</a><?php } ?>
  </dd>
  <!--stuff to show terrain in missions ends here-->

</dl>

<h3 class="heading heading-h3">
  Briefing
  <span class="span">
    <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=briefing" class="colorfont">Change</a><?php } ?>
  </span>
</h3>

<div lang="sv">
  <p><?php echo $mission->getBriefing();?></p>
</div>

<h3 class="heading heading-h3">
  Debriefing
  <span class="span">
    <?php if ($admin or $gm) {?><a href="index.php?url=missions/edit.php&mission=<?php echo $mission->getId();?>&what=debriefing" class="colorfont">Change</a><?php } ?>
  </span>
</h3>

<div lang="sv">
  <p><?php echo $mission->getDebriefing();?></p>
</div>
</div>
