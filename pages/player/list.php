<h1 class="heading heading-h1">
  Players
  <?php if ($_SESSION['level']>=3) { ?>
    <span class="span">
      <a href="index.php?url=player/create.php">Create player</a>
    </span>
  <?php } ?>
</h1>

<?php
$userController = new UserController();
$playerController = new PlayerController();
$user = $userController->getCurrentUser();
$platoonController = new PlatoonController();
$playerId = 0;
if (array_key_exists('player', $_GET)) {
  $playerId = $_GET['player'];
}
if ($user->isAdmin() || $user->getId() == $playerId) {
?>
        <ul class="list">
        <?php
         $players = $playerController->getAllPlayers();
        $previousLetter = null;
        foreach ($players as $player) {
          $currentLetter = $player->getNameWithNickname()[0];
          if ($previousLetter != $currentLetter) {
            echo "<li>". $currentLetter ."</li>";
          }
          $previousLetter = $currentLetter; ?>
                <li>
                    <?php if ($user->isAdmin() || $user->getId() == $player->getId()) { ?>
                      <a href="index.php?url=player/edit.php&player=<?php echo $player->getId(); ?>"><?php
                    }
                    echo stripslashes($player->getNameWithNickname());
                    if ($user->isAdmin() || $user->getId() == $player->getId()) { ?>
                      </a><?php
                    }
                    ?>
                </li>
        <?php }
    ?>
    </ul>
<?php
} else {
    include("components/403.php");
}
?>
