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

    if ($playerId > 0) {
        $player = $playerController->getPlayer($playerId)
        ?>

      <h2 class="heading heading-h2"><?php echo stripslashes($player->getNameWithNickname()); ?></h2>

        <form class="form" method="post" action="actions/player.php?what=modify">
          <input type="hidden" name="id" value="<?php echo $player->getId(); ?>">

          <label for="forname">
            Firstname
            <input type="text" id="forname" name="forname" required value="<?php echo stripslashes($player->getGivenName()); ?>">
          </label>

          <label for="lastname">
            Lastname
            <input type="text" id="lastname" name="lastname" value="<?php echo stripslashes($player->getSurname()); ?>">
          </label>

          <label for="nickname">
            Nickname
            <input type="text" id="nickname" name="nickname" value="<?php echo stripslashes($player->getNickname()); ?>">
          </label>

          <fieldset>
            <legend>Use nickname</legend>
            <label for="use_nickname">
              <input type="radio" id="use_nickname_1" name="use_nickname" value="1" <?php echo ($player->getUseNickname() == "1") ? ("checked") : (""); ?>> Yes
            </label>
            <label for="use_nickname">
              <input type="radio" id="use_nickname_0" name="use_nickname" value="0" <?php echo ($player->getUseNickname() == "0") ? ("checked") : (""); ?>> No
            </label>
          </fieldset>

          <label for="emailadress">
            Email
            <input type="email" id="emailadress" name="emailadress" required pattern=".+@.+\..+" value="<?php echo stripslashes($player->getEmailaddress()); ?>">
          </label>

          <label for="password">
            Password
            <input type="password" id="password" name="password">
          </label>

          <label for="platoon_id">
            Platoon
          <?php
                    $platoons = $platoonController->getPlatoons(); ?>
                    <select id="platoon_id" name="platoon_id"><?php
                    foreach ($platoons as $platoon) {
                      $platoonId = $platoon->getId(); ?>
                      <option value="<?php echo $platoonId; ?>" <?php
                      if ($platoonId == $player->getPlatoonId()) {
                        echo "selected";
                      } elseif (!$user->isAdmin()) {
                        echo "disabled";
                      }
                      ?> ><?php echo $platoon->getName(); ?></option><?php
                    } ?>
                    </select>
          </label>

          <label for="discordid">
            Discord Id (17 or 18 digits)
            <input type="text" id="discordid" name="discordid" pattern="\d{17,18}" value="<?php echo $player->getDiscordId(); ?>">
          </label>

          <label for="active">
            Is active
            <input type="checkbox" id="active" name="active" <?php echo ($player->getPlayerActive()==1) ? ('checked="1"') : (""); ?>>
          </label>

          <input class="button" type="submit" value="Modify Player">
        </form>
    <?php } ?>
<?php
} else {
    include("components/403.php");
}
?>
