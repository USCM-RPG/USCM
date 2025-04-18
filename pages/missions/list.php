<h1 class="heading heading-h1">
  Missions
  <?php if ($_SESSION['level']>=2) { ?>
    <span class="span">
      <a href="index.php?url=missions/create.php">Create mission</a>
    </span>
  <?php } ?>
</h1>

<?php
$tagController = new TagController();
$tags = $tagController->getAllTags();
?>

<label for="select-tag" style="display: block; margin-bottom: 20px;">
  Select tag
  <select id="select-tag" onchange="window.location.href = this.value">
    <option value="index.php?url=missions/list.php">All tags</option>
    <?php foreach ($tags as $tag) { ?>
      <option
        <?php if (array_key_exists("tag", $_GET) && $_GET['tag'] == $tag->getId()) echo "selected"; ?>
        value="index.php?url=missions/list.php&tag=<?php echo $tag->getId();?>"
      >
        <?php echo $tag->getName();?>
      </option>
    <?php } ?>
  </select>
</label>

<ul class="list">
<?php
$missionController = new MissionController();

if (isset($_GET['tag'])) {
  $missions = $tagController->getMissionsForTag($_GET['tag']);
} else {
  $missions = $missionController->getMissions();
}
foreach ($missions as $mission) { ?>
  <li>
    <span class="tag tag-<?php echo strtolower($mission->getPlatoonShortName());?>" style="view-transition-name: transition-mission-platoon-<?php echo $mission->getId();?>;"><?php echo $mission->getPlatoonShortName();?></span>
    <a
      href="index.php?url=missions/details.php&id=<?php echo $mission->getId();?>"
      style="view-transition-name: transition-mission-<?php echo $mission->getId();?>;"
    >
      <?php echo $mission->getShortName();?>
    </a>
    <span style="view-transition-name: transition-mission-name-<?php echo $mission->getId();?>;">
      <?php echo $mission->getName();?>
    </span>
  </li>
<?php } ?>
</ul>
