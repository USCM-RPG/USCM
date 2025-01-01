<section>
<h2 class="heading heading-h2">Mission tags</h2>
<ul class="list">
<fieldset class="form--inline grid grid--1x2">

<?php
$tagController = new TagController();
$tags = $tagController->getAllTags();
$missionController = new MissionController();

#this stuff list all current tags
foreach ($tags as $tag) { 
?>
  <li>
    <a href="index.php?url=tags/list.php&selectedtag=true&selectedtagid=<?php echo $tag->getId();?>&id=<?php echo $tag->getName();?>"><?php echo $tag->getName();?></a>
  </li>
<?php } ?>
</ul>
</section>


<!--empty section for space between the two sections--> 
<section>
<section> <br> </section>

<!--this stuff shows all missions with the selected tag--> 
<legend>
<?php
if ($_GET['selectedtag']) {
$missionIds=$tagController->getMissionsForTag($_GET['selectedtagid']); ?>
<h3 class="heading heading-h3">Missions with the selected tag</h3>
<fieldset class="form--inline grid grid--1x3--missiontags">
<?php foreach ($missionIds as $missionId) { 
$mission = $missionController->getMission($missionId);
?>
    <span class="tag tag-<?php echo strtolower($mission->getPlatoonShortName());?>"><?php echo $mission->getPlatoonShortName();?></span>
    <a href="index.php?url=missions/details.php&id=<?php echo $mission->getId();?>"><?php echo $mission->getShortName();?></a>
    <?php echo $mission->getName();?>

<?php } ?>
<?php } ?>
</legend>
</section>
