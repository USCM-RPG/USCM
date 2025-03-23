<?php
$userController = new UserController();
$playerController = new PlayerController();
$user = $userController->getCurrentUser();
$playerId = 0;
if (array_key_exists('player', $_GET)) {
  $playerId = $_GET['player'];
}
if ($user->isAdmin() || $user->getId() == $playerId) {
  ?>
  <?php
  if ($playerId > 0) {
    $player = $playerController->getPlayer($playerId)
    ?>

    <h1 class="heading heading-h1">My page</h1>
    <h2 class="heading heading-h2">
      <?php echo stripslashes($player->getNameWithNickname()); ?>
      <span class="span">
        <a href="index.php?url=player/edit.php&player=<?php echo $_SESSION['user_id']; ?>">Change</a>
      </span>
    </h2>

    <h3 class="heading heading-h3">My characters</h3>

    <?php
    $characterController = new CharacterController();
    $characters = $characterController->getCharactersByUser($playerId);
    ?>

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
      </tr>
      </thead>
      <tbody>
    <?php
    foreach ($characters as $character) {
      $lastMission = lastMissionForCharacter($character->getId());
      $missionCount = getNumberOfMissionsForCharacter($character->getId());
      ?>
      <tr>
        <td>
          <?php echo $character->getRankShort();?>
        </td>
        <td>
          <a href="index.php?url=characters/edit.php&character_id=<?php echo $character->getId();?>">
            <?php echo $character->getGivenName();?>
            <?php echo $character->getSurname();?>
          </a>
        </td>
        <td>
          <?php echo $character->getSpecialtyName();?>
        </td>
        <td>
          <?php echo $missionCount ?>
        </td>
        <td>
          <?php if ($lastMission) {?>
            <a href="index.php?url=missions/details.php&id=<?php echo $lastMission['mission_id']?>">
              <?php echo $lastMission['mission_name_short']?>
            </a>
          <?php } ?>
        </td>
        <td>
          <?php
          $glory = $character->getGlory();
          if ($glory != "0") {
            $medals = "";
            $commendationsArray = getCommendationsForCharacter($character->getId());
            foreach ($commendationsArray as $key => $value) {
              if ($commendationsArray[$key]['medal_short'] != "") $medals = $medals . " " . $commendationsArray[$key]['medal_short'];
            }
          ?>
          <details class="details">
            <summary><?php echo $glory;?></summary>
            <?php echo $medals;?>
          </details>
            <?php
          }
          ?>
        </td>
        <td>
          <span class="no-wrap">
            * <?php echo $character->getEnlistedDate();?>
          </span>
          <?php
          if ($character->getStatus() == "Dead" || $character->getStatus() == "Retired") {
            ?>
            <span class="no-wrap">
              † <?php echo $lastMission['date'] ?? '';?>
            </span>
            <?php
          }
          ?>
        </td>
        <td>
          <?php echo $character->getStatus();?>
        </td>
      </tr>
      <?php
    }
    ?>
      </tbody>
    </table>

    <h3 class="heading heading-h3">My missions</h3>

    <?php
    $missionController = new MissionController();
    $missions = $missionController->getMissionsByUser($playerId);
    ?>
    <ul class="list">
      <?php
      foreach ($missions as $mission) {
        ?>
        <li>
          <span class="tag tag-<?php echo strtolower($mission->getPlatoonShortName());?>"><?php echo $mission->getPlatoonShortName();?></span>
          <a href="index.php?url=missions/details.php&id=<?php echo $mission->getId();?>"><?php echo $mission->getShortName();?></a>
          <?php echo $mission->getName();?>
        </li>
        <?php
      }
      ?>
    </ul>

    <?php
    $missionController = new MissionController();
    $missions = $missionController->getMissionsGmByUser($playerId);
    ?>
    <?php
    if ($missions) {
      ?>
      <h3 class="heading heading-h3">My GMed missions</h3>
      <ul class="list">
        <?php
        foreach ($missions as $mission) {
          ?>
          <li>
            <span class="tag tag-<?php echo strtolower($mission->getPlatoonShortName());?>"><?php echo $mission->getPlatoonShortName();?></span>
            <a href="index.php?url=missions/details.php&id=<?php echo $mission->getId();?>"><?php echo $mission->getShortName();?></a>
            <?php echo $mission->getName();?>
          </li>
          <?php
        }
        ?>
      </ul>
      <?php
    }
    ?>

    <?php
  }
  ?>
  <?php
} else {
  include("components/403.php");
}
?>
