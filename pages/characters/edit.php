<?php

$characterId = $_GET['character_id'];
$userController = new UserController();
$platoonController = new PlatoonController();
$rankController = new RankController();
$characterController = new CharacterController();
$expertiseController = new ExpertiseController();
$character = new Character($characterId);
$character = $characterController->getCharacter($characterId);
$user = $userController->getCurrentUser();
$canmodify = FALSE;

if ($user->getId() == $character->getPlayerId() || $user->isAdmin() || $user->isGm()) {
  $player = $character->getPlayer();
  $playerPlatoon = NULL;
    ?>
  <h1 class="heading heading-h1">
    Modify character
    <span class="span">
      <a href="index.php?url=characters/list.php&platoon=<?php echo $character->getPlatoonId(); ?>">Back</a>
    </span>
  </h1>
  <h2 class="heading heading-h2">
    <span style="view-transition-name: transition-character-<?php echo $characterId; ?>;">
      <?php echo $character->getGivenName(); ?> <?php echo $character->getSurname(); ?>
    </span>
    <span class="span">
      <a href="index.php?url=characters/details.php&character_id=<?php echo $characterId; ?>">Do you want to know more?</a>
    </span>
  </h2>

  <a href="pages/sheet/create<?php echo $character->getVersion(); ?>.php?character_id=<?php echo $characterId; ?>" target="_blank">Generate character sheet</a> |
  <a href="pages/sheet/create<?php echo $character->getVersion(); ?>.php?character_id=<?php echo $characterId; ?>&backside=1" target="_blank">Generate 2-page character sheet</a>

    <?php
	if ($user->isAdmin() || ($user->isGm() && $character->getPlatoonId() == $user->getPlatoonId())) {
		$canmodify = TRUE;
	}

	if ($canmodify) { ?><form class="form" method="post" action="actions/character.php?action=update_character"><?php } ?>
    <input type="hidden" name="player" value="<?php echo $player->getId(); ?>">
    <input type="hidden" name="character" value="<?php echo $characterId; ?>">

  <div class="grid grid--1x2 mt-20">
    <div>
      Player
      <div style="view-transition-name: transition-character-player-<?php echo $characterId; ?>;">
        <?php echo $player->getName(); ?>
      </div>
    </div>
    <label for="platoon">
      Platoon
      <?php
                $platoons = $platoonController->getPlatoons();
    ?>
                    <select id="platoon" name="platoon">
    <?php foreach ($platoons as $platoon) {
            $platoonId = $platoon->getId();
            if ($platoonId == $character->getPlatoonId()) {
              $playerPlatoon = $platoon;
            } ?>
                            <option value="<?php echo $platoonId; ?>" <?php echo ($platoonId == $character->getPlatoonId()) ? ("selected") : (""); ?> ><?php echo $platoon->getName(); ?></option>
    <?php } ?>
                    </select>
    </label>

    <label for="forname">
      Firstname
      <input type="text" id="forname" name="forname" value="<?php echo $character->getGivenName(); ?>">
    </label>

    <label for="lastname">
      Lastname
      <input type="text" id="lastname" name="lastname" value="<?php echo $character->getSurname(); ?>">
    </label>

    <label for="specialty">
      Specialty
      <select id="specialty" name="specialty">
        <?php $specialties = $characterController->getSpecialties(); ?>
        <?php foreach ($specialties as $specialty) {
                      $specialtyId = $specialty->getId(); ?>
          <option <?php echo ($specialtyId == $character->getSpecialtyId()) ? ("selected") : (""); ?> value="<?php echo $specialtyId; ?>" >
                            <?php echo $specialty->getName(); ?></option>
        <?php } ?>
      </select>
    </label>

    <label for="rank">
      Rank (bör ej ändras om karaktären har fått befordran)
      <select id="rank" name="rank">
        <?php $ranks = $rankController->getRanks(); ?>
        <?php foreach ($ranks as $rank) { ?>
          <option <?php echo ($rank->getId() == $character->getRankId()) ? ("selected") : (""); ?> value="<?php echo $rank->getId(); ?>" >
        <?php echo $rank->getName(); ?></option>
        <?php } ?>
      </select>
    </label>

    <label for="enlisted">
      Enlisted (format: YYYY-MM-DD)
      <input type="text" id="enlisted" name="enlisted" pattern="\d{4}-\d{2}-\d{2}" value="<?php echo $character->getEnlistedDate(); ?>">
    </label>

    <label for="age">
      Age
      <input type="number" id="age" name="age" value="<?php echo $character->getAge(); ?>">
    </label>

    <label for="gender">
      Gender
      <select id="gender" name="gender">
        <option <?php echo ($character->getGender() == "Male") ? ("selected ") : (""); ?>value="Male">Male</option>
        <option <?php echo ($character->getGender() == "Female") ? ("selected ") : (""); ?>value="Female">Female</option>
      </select>
    </label>

    <label for="status">
      Status
      <select id="status" name="status">
          <option <?php echo ($character->getStatus() == "Active") ? ("selected ") : (""); ?>value="Active" >Active</option>
          <option <?php echo ($character->getStatus() == "PoW") ? ("selected ") : (""); ?>value="PoW">PoW</option>
          <option <?php echo ($character->getStatus() == "Retired") ? ("selected ") : (""); ?>value="Retired">Retired</option>
          <option <?php echo ($character->getStatus() == "Dead") ? ("selected ") : (""); ?>value="Dead">Dead</option>
        </select>
    </label>

    <label for="status_desc">
      Status Description
      <input type="text" id="status_desc" name="status_desc" value="<?php echo $character->getStatusDescription(); ?>" size="60"></td>
    </label>
  </div>

  <fieldset class="form--inline grid grid--small grid--leftalign">
    <legend>Encounters</legend>

    <label for="cbalien">
      <input type="checkbox" id="cbalien" name="cbalien" <?php echo ($character->getEncounterAlien()==1) ? ('checked="1"') : (""); ?>>
      Alien
    </label>

    <label for="cbgrey">
      <input type="checkbox" id="cbgrey" name="cbgrey" <?php echo ($character->getEncounterGrey()==1) ? ('checked="1"') : (""); ?>>
      Grey
    </label>

    <label for="cbpredator">
      <input type="checkbox" id="cbpredator" name="cbpredator" <?php echo ($character->getEncounterPredator()==1) ? ('checked="1"') : (""); ?>>
      Predator
    </label>

    <label for="cbai">
      <input type="checkbox" id="cbai" name="cbai" <?php echo ($character->getEncounterAI()==1) ? ('checked="1"') : (""); ?>>
      AI/Android
    </label>

    <label for="cbarachnid">
      <input type="checkbox" id="cbarachnid" name="cbarachnid" <?php echo ($character->getEncounterArachnid()==1) ? ('checked="1"') : (""); ?>>
      Arachnid
    </label>
  </fieldset>

  <fieldset class="form--inline grid grid--small">
    <legend>Attributes</legend>
            <?php
            //Ta ut alla attribut
            $allattributes = $characterController->getAttributes();
            $characterAttributes = $character->getAttributes();
            foreach ($allattributes as $attribute) {
              $attributeId = $attribute->getId();
                ?>
              <label for="attribute_<?php echo $attribute->getId();?>">
                <?php echo $attribute->getName();?>
                <input
                  type="number"
                  id="attribute_<?php echo $attribute->getId();?>"
                  name="attribute[<?php echo $attribute->getId();?>]"
                  min="0"
                  max="10"
                  value="<?php echo (array_key_exists($attributeId, $characterAttributes)) ? ($characterAttributes[$attributeId]) : (""); ?>"
                >
              </label>
    <?php } ?>
  </fieldset>

  <fieldset class="form--inline grid grid--small">
    <legend>Points</legend>

    <label for="cp">
      Stunts
      <input type="number" id="cp" name="cp" min="0" value="<?php echo $character->getCoolPoints(); ?>">
    </label>

    <label for="fp">
      Stress
      <input type="number" id="fp" name="fp" min="0" value="<?php echo $character->getFearPoints(); ?>">
    </label>

    <label for="lp">
      Leadership Points
      <input type="number" id="lp" name="lp" min="0" value="<?php echo $character->getLeadershipPoints(); ?>">
    </label>

    <label for="pp">
      Psycho Points
      <input type="number" id="pp" name="pp" min="0" value="<?php echo $character->getPsychoPoints(); ?>">
    </label>

    <label for="xp">
      Unused XP
      <input type="number" id="xp" name="xp" min="0" value="<?php echo $character->getUnusedXp(); ?>">
    </label>
    
    <label for="scrating">
      Unused XP
      <input type="number" id="scrating" name="scrating" min="0" value="<?php echo $character->getShipClassRating(); ?>">
    </label>
  </fieldset>

  <fieldset class="form--inline grid grid--small">
    <legend>Skills</legend>
            <?php
            $allSkills = array();
            if ($character->getVersion() > 2) {
				$allSkills = $characterController->getSkillsGrouped($character->getVersion());
			} else {
				$allSkills = $characterController->getSkillsGrouped();
			}
            $characterSkills = $character->getSkillsGrouped();
            $previousGroupId = null;
            foreach($allSkills as $skill) {
              $skillId = $skill->getId();
              if ($previousGroupId != $skill->getSkillGroupId()) {
                echo "<div class='skill-category colorfont'>". $skill->getSkillGroupName() ."</div>";
              }
              $previousGroupId = $skill->getSkillGroupId();
            ?>
    <label for="skills_<?php echo $skillId; ?>">
      <?php echo $skill->getName(); ?>
                  <input type="number" min="0" max="10" id="skills_<?php echo $skillId; ?>" name="skills[<?php echo $skillId; ?>]" value="<?php echo (array_key_exists($skillId, $characterSkills)) ? ($characterSkills[$skillId]['value']) : (""); ?>">
    </label>
                  <input type="hidden" name="optional[<?php echo $skillId; ?>]" value="<?php echo $skill->getOptional(); ?>">
            <?php } ?>
  </fieldset>

<?php
if ($character->getVersion() > 2) {
	$characterExpertise  = $character->getExpertise();
	$expertiseGroups = $expertiseController->getAllExpertiseGroups();

	foreach($expertiseGroups as $key => $value) {
		?>
		<fieldset class="form--inline grid grid--small grid--leftalign">
			<legend><?php echo $value['name'] . ' Expertise' ?></legend>
			<?php
			$expertises = $expertiseController->getExpertiseByGroup($key);
			foreach($expertises as $expertise) {
				$expertiseId = $expertise->getId();
				?>
				<label for="expertise_<?php echo $expertiseId; ?>">
					<input type="checkbox" id="expertise_<?php echo $expertiseId; ?>" name="expertise[<?php echo $expertiseId; ?>]" <?php echo (array_key_exists($expertiseId, $characterExpertise)) ? ("checked") : (""); ?>>
					<?php echo $expertise->getName(); ?> (<?php echo $expertise->getValue(); ?>)
				</label>
				<?php
			}
			?>
		</fieldset>
		<?php
	}
}
?>

  <fieldset class="form--inline grid grid--small grid--leftalign">
    <legend>Traits</legend>
            <?php
            $allTraits = $characterController->getTraits();
            $characterTraits = $character->getTraits();
            foreach ($allTraits as $trait) {
              $traitId = $trait->getId();
                ?>
              <label for="traits_<?php echo $traitId; ?>">
                <input type="checkbox" id="traits_<?php echo $traitId; ?>" name="traits[<?php echo $traitId; ?>]" <?php echo (array_key_exists($traitId, $characterTraits)) ? ("checked") : (""); ?>>
                <?php echo $trait->getName(); ?>
              </label>
            <?php } ?>
  </fieldset>

  <fieldset class="form--inline grid grid--small grid--leftalign">
    <legend>Advantages</legend>
            <?php
            $allAdvantages = $characterController->getAdvantages();
            foreach ($allAdvantages as $advantage) {
              $advantageId = $advantage->getId();
            ?>
              <label for="advs_<?php echo $advantageId; ?>">
                <input type="checkbox" id="advs_<?php echo $advantageId; ?>" name="advs[<?php echo $advantageId; ?>]" <?php echo ($character->hasCharacterAdvantage($advantageId)) ? ("checked") : (""); ?> >
                <?php echo $advantage->getName() . " (" . $advantage->getValue() . ")"; ?>
              </label>
            <?php } ?>
  </fieldset>

  <fieldset class="form--inline grid grid--small grid--leftalign">
    <legend>Disadvantages</legend>
            <?php
            $allDisadvantages = $characterController->getDisadvantages();
            foreach ($allDisadvantages as $disadvantage) {
              $disadvantageId = $disadvantage->getId();
                ?>
              <label for="disadvs_<?php echo $disadvantageId; ?>">
                <input type="checkbox" id="disadvs_<?php echo $disadvantageId; ?>" name="disadvs[<?php echo $disadvantageId; ?>]" <?php echo ($character->hasCharacterDisadvantage($disadvantageId)) ? ("checked") : (""); ?>>
                <?php echo $disadvantage->getName() . " (" . $disadvantage->getValue() . ")"; ?>
              </label>
            <?php } ?>
  </fieldset>


<?php
if ($character->getVersion() < 3) {
?>
  <fieldset class="form--inline grid grid--small grid--leftalign">
    <legend>Certificates</legend>
            <?php
            $allPlatoonCertificates = array();
            foreach ($playerPlatoon->getCertificates() as $certificate) {
              $allPlatoonCertificates[] = $certificate->getId();
            }
            $allCertificates = $characterController->getCertificates();
            $characterCertificates = $character->getCertsForCharacterWithoutReqCheck();
            $enumerate_disadv = TRUE;
            foreach ($allCertificates as $certificate) {
              $certificateId = $certificate->getId();
                ?>
              <label for="certs_<?php echo $certificateId; ?>">

                <input type="checkbox" id="certs_<?php echo $certificateId; ?>" name="certs[<?php echo $certificateId; ?>]" <?php echo (array_key_exists($certificateId, $characterCertificates)) ? ("checked ") : (""); echo (in_array($certificateId, $allPlatoonCertificates)) ? ("disabled ") : (""); ?>>
                <?php echo $certificate->getName(); ?>
              </label>
            <?php } ?>
  </fieldset>
<?php } ?>

    <?php if ($canmodify) { ?>
      <input class="button" type="submit" value="Modify Character">
    </form>
    <?php }
}
?>
