<?php
$platoonController = new PlatoonController();
$rankController = new RankController();
$medalController = new MedalController();
$characterController = new CharacterController();

$admin=($_SESSION['level']>=3)?(TRUE):(FALSE);
$gm=($_SESSION['level']==2)?(TRUE):(FALSE);
if (!array_key_exists('platoon', $_GET)) {
	if (array_key_exists('platoon_id', $_SESSION)) {
		$_GET['platoon']=$_SESSION['platoon_id'];
	} else {
		$_GET['platoon']=1;
	}
}

$where="AND c.platoon_id={$_GET['platoon']}";

$charactersql="SELECT rank_id,c.id as cid,c.forname,c.lastname,DATE_FORMAT(c.enlisted,'%Y-%m-%d') as enlisted,c.status,
              rank_short,specialty_name, p.forname as playerforname,p.lastname as playerlastname,p.nickname,
              p.use_nickname,c.userid,c.version
          FROM uscm_characters c
          LEFT JOIN Users as p ON c.userid = p.id
          LEFT JOIN uscm_ranks
            ON uscm_ranks.character_id = c.id
          LEFT JOIN uscm_rank_names
            ON uscm_rank_names.id =
              uscm_ranks.rank_id
          LEFT JOIN uscm_specialty
            ON uscm_specialty.character_id = c.id
          LEFT JOIN uscm_specialty_names
            ON uscm_specialty_names.id =
              uscm_specialty.specialty_name_id
              WHERE c.status != 'Dead' AND c.status != 'Retired' AND p.id != '0' AND p.id != '59' {$where}
              ORDER BY rank_id DESC";
$npcsql="SELECT c.id as cid,c.forname,c.lastname,DATE_FORMAT(c.enlisted,'%Y-%m-%d') as enlisted,c.status,
              rank_id,rank_short,specialty_name,c.userid,c.version
          FROM uscm_characters c
          LEFT JOIN Users as p ON c.userid = p.id
          LEFT JOIN uscm_ranks
            ON uscm_ranks.character_id = c.id
          LEFT JOIN uscm_rank_names
            ON uscm_rank_names.id =
              uscm_ranks.rank_id
          LEFT JOIN uscm_specialty
            ON uscm_specialty.character_id = c.id
          LEFT JOIN uscm_specialty_names
            ON uscm_specialty_names.id =
              uscm_specialty.specialty_name_id
              WHERE c.status != 'Dead' AND c.status != 'Retired' AND (p.id = '0' OR p.id = '59') {$where}
              ORDER BY rank_id DESC";

//echo $charactersql . "<br><br><br><br><br><br>";

?>

<h1 class="heading heading-h1">
  Characters
  <?php if ($_SESSION['level']>=2) { ?>
    <span class="span">
      <a href="index.php?url=characters/create3.php">Create character v3</a> <span class="tag tag-bf5">current version</span> <a href="index.php?url=characters/create2.php">Create character v2</a> <span class="tag">old version</span>
    </span>
  <?php } ?>
</h1>

<label for="select-platoon" style="display: block; margin-bottom: 20px;">
  Select platoon
  <select id="select-platoon" onchange="window.location.href = this.value">
    <option value=""></option>
    <?php
      $platoons = $platoonController->getPlatoons();
      foreach ($platoons as $platoon ) {
    ?>
        <option
          <?php if (array_key_exists("platoon", $_GET) && $_GET['platoon'] == $platoon->getId()) echo "selected"; ?>
          value="index.php?url=characters/list.php&platoon=<?php echo $platoon->getId(); ?>"
        >
          <?php echo $platoon->getName(); ?> (<?php echo $platoon->getShortName(); ?>)
        </option>
    <?php
      }
    ?>
  </select>
</label>

<?php if ($_GET['platoon'] == "1") { ?>
  Assigned ship: USS Deliverance (Conestoga-class frigate)<br>
<?php } elseif ($_GET['platoon'] == "5") {?>
  Assigned ship: USS Nautilus (Conestoga-class frigate)<br>
<?php } ?>

<table class="table">
  <caption>
    Player Characters
    <hr class="line">
  </caption>
  <thead>
  <tr>
    <th>Rank</th>
    <th>Name</th>
    <th>Specialty</th>
    <th>Missions</th>
	  <th>Last</th>
    <th>Glory</th>
    <th>Tour of Duty</th>
    <th>Status</th>
    <th>Player</th>
  </tr>
  </thead>
  <tbody>
<?php
  $characterarray = listCharacters($charactersql, "alive");
  foreach ($characterarray as $character) { ?>
  <tr><?php $overlib = false;
  if ($_SESSION['level']>=1  ) {
    $overlib = true;
    $attributearray = characterAttributes($character['cid']);
    $attributearray = attribute2visible($attributearray);
    $certificatearray = certificates($character['cid'],$_GET['platoon']);
    $overlibtext = "";
    foreach ( $attributearray as $id => $key ) {
      $attrib = $key;
      $overlibtext = $overlibtext . htmlentities($attrib,ENT_QUOTES) . "<br>";
    }
    $overlibtext = $overlibtext . "<br>";
    foreach ( $certificatearray as $id => $key ) {
      $cert = $key['name'];
      $cert2 = htmlentities($cert,ENT_QUOTES);
      $overlibtext = $overlibtext . $cert2 . "<br>";
    }
    $traitarray = traits($character['cid']);
    $traitarray ? $overlibtext = $overlibtext . "<br>": "";
    foreach ( $traitarray as $id => $key ) {
      $trait = $key['trait_name'];
      $overlibtext = $overlibtext . htmlentities($trait,ENT_QUOTES) . "<br>";
    }
    $allAdvantages = $characterController->getCharactersVisibleAdvantages($character['cid']);
    count($allAdvantages) > 0 ? $overlibtext = $overlibtext . "<br>": "";
    foreach ($allAdvantages as $advantage) {
      $overlibtext = $overlibtext . htmlentities($advantage->getName(), ENT_QUOTES) . "<br>";
    }
    $visibleDisadvantages = $characterController->getCharactersVisibleDisadvantages($character['cid']);
    count($visibleDisadvantages) > 0 ? $overlibtext = $overlibtext . "<br>": "";
    foreach ($visibleDisadvantages as $disadvantage) {
      $overlibtext = $overlibtext . htmlentities($disadvantage->getName(), ENT_QUOTES) . "<br>";
    }
  }
  $lastMission = lastMissionForCharacter($character['cid']);
    ?><td><?php echo $character['rank_short'];?></td>
    <td <?php if ($overlib) {?>class="popover"<?php } ?>><?php
    $link = false;
    if ($admin || $gm || $_SESSION['user_id']==$character['userid']) { $link = true;?>
        <a
          href="index.php?url=characters/edit.php&character_id=<?php echo $character['cid'];?>"
          style="view-transition-name: transition-character-<?php echo $character['cid'];?>;"
        ><?php }
    ?><?php echo $character['forname'] . " " . $character['lastname'];?><?php echo $link ? "</a>" : ""; ?>
          <?php if ($overlib) {?>
            <div class="popover-panel">
              <?php echo $overlibtext ?>
            </div>
          <?php } ?>
    </td>
    <td><?php echo $character['specialty_name'];?></td>
    <td class="center"><?php echo $character['missions'];?></td>
	  <td>
      <?php if ($lastMission) {?>
        <a href="index.php?url=missions/details.php&id=<?php echo $lastMission['mission_id']?>">
          <?php echo $lastMission['mission_name_short']?>
        </a>
      <?php } ?>
    </td>
<?php
      $medals = "";
      $glory = 0;
      $commendationsArray = getCommendationsForCharacter($character['cid']);
      foreach ($commendationsArray as $key => $value) {
        if ($commendationsArray[$key]['medal_short'] != "") $medals = $medals . " " . $commendationsArray[$key]['medal_short'];
        $glory = $glory + $commendationsArray[$key]['medal_glory'];
      }
      ?>
    <td>
      <?php if ($glory != "0") {?>
      <details class="details">
        <summary><?php echo $glory;?></summary>
        <?php echo $medals;?>
      </details>
      <?php } ?>
    </td>
    <td>
      <span class="no-wrap">
        * <?php echo $character['enlisted'];?>
      </span>
    </td>
    <td>
      <?php if ($character['status'] != "Active") {
        echo $character['status'];
        }
        else {
          if ($character['version'] < 3) {
          echo '<img src="assets/icons/cryo-icon.svg" class="icon--text" alt="In cryo" title="In cryo">';
          }
        }
      ?>
    </td>
    <td>
      <?php if ($admin || $_SESSION['user_id']==$character['userid']) { ?>
        <a href="index.php?url=player/edit.php&player=<?php echo $character['userid'] ?>" style="view-transition-name: transition-character-player-<?php echo $character['cid'];?>;"><?php
        }
        echo ($character['use_nickname']=="1")?(stripslashes($character['nickname'])):(stripslashes($character['playerforname']) . " " . stripslashes($character['playerlastname']));
        if ($admin || $_SESSION['user_id']==$character['userid']) { ?>
          </a><?php
        }
      ?>
    </td>
  </tr>
<?php unset($medals,$glory);
  } ?>
  </tbody>
</table>

<table class="table mt-20">
  <caption class="colorfont">
    Non-Player Characters
    <hr class="line">
  </caption>
  <thead>
  <tr>
    <th>Rank</th>
    <th>Name</th>
    <th>Specialty</th>
    <th>Missions</th>
    <th>Glory</th>
    <th>Tour of Duty</th>
    <th>Status</th>
  </tr>
  </thead>
  <tbody>
<?php
  $npcarray = listCharacters($npcsql,"alive");
  $medals = "";
  $glory = 0;
  foreach ($npcarray as $npc) { ?>
  <tr><?php $overlib = false;
  if ( $_SESSION['level']>=1  ) {
    $overlib = true;
      $attributearray = characterAttributes($character['cid']);
      $attributearray = attribute2visible($attributearray);
      $certificatearray = certificates($npc['cid'],$_GET['platoon']);
    $overlibtext = "";
    foreach ( $attributearray as $id => $key ) {
      $attrib = $key;
      $overlibtext = $overlibtext . htmlentities($attrib,ENT_QUOTES) . "<br>";
    }
    $overlibtext = $overlibtext . "<br>";
    foreach ( $certificatearray as $id => $key ) {
      $cert = $key['name'];
      $cert2 = htmlentities($cert,ENT_QUOTES);
      $overlibtext = $overlibtext . $cert2 . "<br>";
    }
    $traitarray = traits($npc['cid']);
    $traitarray ? $overlibtext = $overlibtext . "<br>": "";
    foreach ( $traitarray as $id => $key ) {
      $trait = $key['trait_name'];
      $overlibtext = $overlibtext . htmlentities($trait,ENT_QUOTES) . "<br>";
    }
    $advarray = advantages($npc['cid'], true);
    $advarray ? $overlibtext = $overlibtext . "<br>": "";
    foreach ( $advarray as $id => $key ) {
      $adv = $key['advantage_name'];
      $overlibtext = $overlibtext . htmlentities($adv,ENT_QUOTES) . "<br>";
    }
    $disadvantages = disadvantages($npc['cid'], true);
    $disadvantages ? $overlibtext = $overlibtext . "<br>": "";
    foreach ( $disadvantages as $id => $key ) {
      $dis = $key['disadvantage_name'];
      $overlibtext = $overlibtext . htmlentities($dis,ENT_QUOTES) . "<br>";
    }
  }
    ?><td><?php echo $npc['rank_short'];?></td>
    <td <?php if ($overlib) {?>class="popover"<?php } ?>>
      <?php if ($admin || $gm || $_SESSION['user_id']==$npc['userid']) { ?>
        <a href="index.php?url=characters/edit.php&character_id=<?php echo $npc['cid'];?>">
          <?php echo $npc['forname'] . " " . $npc['lastname'];?>
        </a>
      <?php } else { ?>
        <?php echo $npc['forname'] . " " . $npc['lastname'];?>
      <?php } ?>
      <?php if ($overlib) {?>
        <div class="popover-panel">
          <?php echo $overlibtext ?>
        </div>
      <?php } ?>
    </td>
    <td><?php echo $npc['specialty_name'];?></td>
<?php
  $missionCount = getNumberOfMissionsForCharacter($npc['cid'])?>
    <td><?php echo $missionCount;?></td>
<?php
  $medals = "";
  $glory = 0;
  $commendationsArray = getCommendationsForCharacter($npc['cid']);
  foreach ($commendationsArray as $key => $value) {
    if ($commendationsArray[$key]['medal_short'] != "") $medals = $medals . " " . $commendationsArray[$key]['medal_short'];
    $glory = $glory + $commendationsArray[$key]['medal_glory'];
  }
?>
    <td>
      <?php if ($glory!=0) {?>
        <details class="details">
          <summary><?php echo $glory;?></summary>
          <?php echo $medals;?>
        </details>
      <?php } ?>
    </td>
    <td>
      <span class="no-wrap">
        * <?php echo $npc['enlisted'];?>
      </span>
    </td>
    <td>
      <?php if ($npc['status'] != "Active") {
        echo $npc['status'];
        }
        else {
          if ($npc['version'] < 3) {
          echo '<img src="assets/icons/cryo-icon.svg" class="icon--text" alt="In cryo" title="In cryo">';
          }
        }
      ?>
    </td>
  </tr>
<?php
  unset($medals,$glory);
}
?>
  </tbody>
</table>

<?php if ($_GET['platoon'] == "1" || $_GET['platoon'] == "5" || $_GET['platoon'] == "6") { ?>
<table class="table">
  <caption>
    Special Non-Player Characters
    <hr class="line">
  </caption>
  <thead>
  <tr>
    <th>Rank</th>
    <th>Name</th>
    <th>Specialty</th>
    <th>Tour of Duty</th>
	  <th>Status</th>
  </tr>
  </thead>
  <tbody>
<?php if ($_GET['platoon'] == "1") {?>
  <tr>
    <td>Lieutenant</td>
    <td>Louise Wheatly</td>
    <td>Officer</td>
    <td>
      <span class="no-wrap">
        * 2019-08-10
      </span>
      <span class="no-wrap">
        †
      </span>
    </td>
    <td>KIA</td>
  </tr>
  <tr>
    <td>Lieutenant</td>
    <td>Michael Brixton</td>
    <td>Officer</td>
    <td>
      <span class="no-wrap">
        * 2000-10-14
      </span>
    </td>
    <td></td>
  </tr>
  <tr>
    <td>Android</td>
    <td>Garth</td>
    <td>Synthetic</td>
    <td>
      <span class="no-wrap">
        * 2000-11-28
      </span>
    </td>
    <td></td>
  </tr>
<?php } elseif ($_GET['platoon'] == "5") {?>
  <tr>
    <td>Lieutenant</td>
    <td>Lionel Lee</td>
    <td>Officer</td>
    <td>
      <span class="no-wrap">
        * 2018-01-21
      </span>
    </td>
    <td></td>
  </tr>
  <tr>
    <td>Android</td>
    <td>Ishmael</td>
    <td>Synthetic</td>
    <td>
      <span class="no-wrap">
        * 2018-01-21
      </span>
    </td>
    <td></td>
  </tr>
<?php } elseif ($_GET['platoon'] == "6") {?>
  <tr>
    <td>Lieutenant</td>
    <td>Drake</td>
    <td>Officer</td>
    <td>
      <span class="no-wrap">
        * 2017-10-31
      </span>
    </td>
    <td></td>
  </tr>
<?php } ?>
  </tbody>
</table>
<?php } ?>

<hr class="line mt-40">

<h2 class="heading heading-h2">Ranks</h2>

<dl class="list-description">
<?php
$ranks = $rankController->getRanks();
foreach ($ranks as $rank) { ?>
    <dt>
      <span class="tag"><?php echo $rank->getShortName() ?></span>
    </dt>
    <dd>
      <?php echo $rank->getName() ?>
    </dd>
<?php } ?>
  </dl>

<hr class="line mt-40">

<h2 class="heading heading-h2">Medals</h2>

<h3 class="heading heading-h3">USCM Medals</h3>

<dl class="list-description">
<?php
$medals = $medalController->getUscmMedals();
foreach ($medals as $medal) { ?>
    <dt class="no-wrap">
      <span class="tag"><?php echo $medal->getShortName() ?></span>
      Glory <?php echo $medal->getGlory() ?>
    </dt>
    <dd>
      <details class="details">
        <summary><?php echo $medal->getName() ?></summary>
        <?php echo $medal->getDescription() ?>
      </details>
    </dd>
<?php } ?>
</dl>

<h3 class="heading heading-h3">Non-USCM Medals</h3>

<dl class="list-description">
<?php
$foreignmedals = $medalController->getForeignMedals();
foreach ($foreignmedals as $medal) { ?>
  <dt class="no-wrap">
    <span class="tag"><?php echo $medal->getShortName() ?></span>
    Glory <?php echo $medal->getGlory() ?>
  </dt>
  <dd>
    <details class="details">
      <summary><?php echo $medal->getName() ?></summary>
      <?php echo $medal->getDescription() ?>
    </details>
  </dd>
<?php } ?>
</dl>
