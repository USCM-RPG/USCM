<?php
$userController = new UserController();
$user = $userController->getCurrentUser();
if ($user->isAdmin() || $user->isGm()) {
?>
  <h1 class="heading heading-h1">Create mission</h1>

<form class="form" method="post" action="actions/mission.php?what=create_mission">
<input type="hidden" name="platoon_id" value="<?php echo $user->getPlatoonId(); ?>">

  <label for="mission">
    Mission
    <input type="text" id="mission" name="mission" required>
  </label>

  <label for="name">
    Name
    <input type="text" id="name" name="name" required>
  </label>

  <label for="date">
    Date
    <input type="text" id="date" name="date" pattern="\d{4}-\d{2}-\d{2}">
  </label>

  <label for="briefing">
    Briefing
    <textarea id="briefing" name="briefing" rows="20"></textarea>
  </label>

  <label for="debriefing">
    Debriefing
    <textarea id="debriefing" name="debriefing" rows="20"></textarea>
  </label>

  <input class="button" type="submit" value="Create Mission">
</form>
<?php }
else {
include("components/403.php");
}?>
