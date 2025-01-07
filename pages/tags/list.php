<?php
$tagController = new TagController();
$tags = $tagController->getAllTags();
?>

<h1 class="heading heading-h1">Missions</h1>

<label for="select-tag" style="display: block; margin-bottom: 20px;">
  Select tag
  <select id="select-tag" onchange="window.location.href = this.value">
    <option value="index.php?url=tags/list.php"></option>
    <?php foreach ($tags as $tag) { ?>
      <option
        <?php if (array_key_exists("id", $_GET) && $_GET['id'] == $tag->getId()) echo "selected"; ?>
        value="index.php?url=tags/list.php&id=<?php echo $tag->getId();?>"
      >
        <?php echo $tag->getName();?>
      </option>
      <?php } ?>
  </select>
</label>

<!--this stuff shows all missions with the selected tag-->
<?php
if (isset ($_GET['id'])) {
  $tagId = $_GET['id'];
  $missionController = new MissionController();
$missionIds=$tagController->getMissionsForTag($tagId); ?>
<ul class="list">
<?php foreach ($missionIds as $missionId) {
$mission = $missionController->getMission($missionId);
?>
  <li>
    <span class="tag tag-<?php echo strtolower($mission->getPlatoonShortName());?>"><?php echo $mission->getPlatoonShortName();?></span>
    <a href="index.php?url=missions/details.php&id=<?php echo $mission->getId();?>"><?php echo $mission->getShortName();?></a>
    <?php echo $mission->getName();?>
  </li>

<?php } ?>
  </ul>
<?php } ?>
