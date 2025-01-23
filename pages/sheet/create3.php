<?php
/*
 * Note: libPDF uses coordinates from the lower left corner
 */
require_once("../../session.php");
require_once("../../functions.php");
require_once("../../classes/iconpdf.php");

$characterId = $_GET['character_id'];
$characterController = new CharacterController();
$playerController = new PlayerController();
$character = $characterController->getCharacter($characterId);
$user = new Player();
$userid = $character->getPlayerId();

if ($user->getId() == $character->getPlayerId() || $user->isAdmin() || $user->isGm()) {
  $platoon_id = $character->getPlatoonId();
  $player = $character->getPlayer();
  $bonuses = new Bonus($characterId); //remove v2 bonuses?
  
  function drawbox($pdf, $txtarray, $xpos, $ypos, $stringlength, $minlines=0) {
    $multilinetext = explode("\n", wordwrap(implode(', ', array_map(function($e){return $e->getName();}, $txtarray)), $stringlength, "\n"));
	$linecount = 0;
	foreach ($multilinetext as $line) {
		pdf_set_text_pos($pdf, $xpos, $ypos);
		pdf_show($pdf, $line);
		$ypos -= 12;
		$linecount++;
	}
	if ($minlines > $linecount) {
		$ypos -= ($minlines - $linecount)*12;
		$linecount = $minlines;
	}
	
	if ($linecount > 0) {
		PDF_rect($pdf, $xpos, $ypos+10, ($stringlength*5)+4, $linecount*12);
	}
	return $ypos;
  }
  
  function drawwhiteboxes($pdf, $xpos, $ypos, $count, $width=8, $height=8) {
	for($i = 1; $i <= $count; $i ++) {
		pdf_rect($pdf, $xpos, $ypos, $width, $height);
		pdf_stroke($pdf);
		$xpos += $width;
	}
  }

  function fontregular($font, $pdf) {
    $font = PDF_load_font($pdf, "Helvetica", "host", 0);
    pdf_setfont($pdf, $font, 10);
  }

  function fontbold($font, $pdf) {
    $font = PDF_load_font($pdf, "Helvetica-Bold", "host", 0);
    pdf_setfont($pdf, $font, 10);
  }

  $aapcolumnone = 50;
  $aapcolumntwo = 110;
  $aapcolumnthree = 120;
  $attribpcolumnthree = 160;
  $aapcolumnfour = 200; // 170
  $aapcolumnfive = 290; // 260
  $aapcolumnsix = 310; // 280

  $pdf = pdf_new();

  pdf_open_file($pdf, "");
  pdf_set_parameter($pdf, "warning", "true");

  pdf_set_info($pdf, "Creator", "www.uscm.se");
  pdf_set_info($pdf, "Author", "Skynet");
  pdf_set_info($pdf, "Title", "Character Sheet, USCM");

  pdf_begin_page($pdf, 595, 842);
  $font = PDF_load_font($pdf, "Helvetica", "host", 0);
  pdf_setfont($pdf, $font, 10);

  PDF_image($pdf, "../../assets/logo/uscm-blip-logo@512px.png", 50, 710, 280, 102);

  fontbold($font, $pdf);
  pdf_set_text_pos($pdf, 50, 690);
  pdf_show($pdf, "Character sheet");
  fontregular($font, $pdf);

  pdf_set_text_pos($pdf, 50, 670);
  pdf_show($pdf, "Player");
  pdf_set_text_pos($pdf, 100, 670);
  pdf_show($pdf, $player->getName());

  pdf_set_text_pos($pdf, 50, 656);
  pdf_show($pdf, "Email");
  pdf_set_text_pos($pdf, 100, 656);
  pdf_show($pdf, $player->getEmailaddress());

  pdf_set_text_pos($pdf, 50, 627);
  pdf_show($pdf, "Name");
  pdf_set_text_pos($pdf, 100, 627);
  pdf_show($pdf, $character->getName());

  pdf_set_text_pos($pdf, 50, 615);
  pdf_show($pdf, "Rank");
  pdf_set_text_pos($pdf, 100, 615);
  pdf_show($pdf, $character->getRankLong());

  pdf_set_text_pos($pdf, 50, 603);
  pdf_show($pdf, "Specialty");
  pdf_set_text_pos($pdf, 100, 603);
  pdf_show($pdf, $character->getSpecialtyName());

  pdf_set_text_pos($pdf, 50, 591);
  pdf_show($pdf, "Enlisted");
  pdf_set_text_pos($pdf, 100, 591);
  pdf_show($pdf, $character->getEnlistedDate());

  pdf_set_text_pos($pdf, 50, 579);
  pdf_show($pdf, "Age");
  pdf_set_text_pos($pdf, 100, 579);
  pdf_show($pdf, $character->getAge());

  pdf_set_text_pos($pdf, 50, 567);
  pdf_show($pdf, "Gender");
  pdf_set_text_pos($pdf, 100, 567);
  pdf_show($pdf, $character->getGender());

  // Attributes
  fontbold($font, $pdf);
  pdf_set_text_pos($pdf, $aapcolumnone, 534);
  pdf_show($pdf, "Attributes");
  pdf_set_text_pos($pdf, $aapcolumntwo, 534);
  pdf_show($pdf, "Level");
//  pdf_set_text_pos($pdf, $attribpcolumnthree, 534);
//  pdf_show($pdf, "Bonus");

  fontregular($font, $pdf);
  $attributearray = characterattributes($characterId);
  $height = 520;
  foreach ( $attributearray as $attributeid => $attribute ) {
    pdf_set_text_pos($pdf, $aapcolumnone, $height);
    pdf_show($pdf, $attribute['attribute_name']);

    pdf_set_text_pos($pdf, $aapcolumntwo, $height);
    pdf_show($pdf, $attribute['value']);
    $attributepointsbonus = $bonuses->attributeBonus("modifier_basic_value", $attributeid);
    pdf_set_text_pos($pdf, $aapcolumntwo + 7, $height);
    print_pdf_bonus($pdf, $attributepointsbonus);

//    pdf_set_text_pos($pdf, $attribpcolumnthree, $height);
//    $attributepointsbonus = $bonuses->attributeBonus("modifier_dice_value", $attributeid);
//    print_pdf_bonus($pdf, $attributepointsbonus);
    $height -= 12;
  }

  // Points
  pdf_set_text_pos($pdf, $aapcolumnone, 388);
  pdf_show($pdf, "Health:");
  
  $health = $character->getHealthPoints();
  $xpos = $aapcolumntwo;
  drawwhiteboxes($pdf, $xpos, 388, $health, 12,12);



pdf_set_text_pos($pdf, $aapcolumnone, 374);
  pdf_show($pdf, "Leadership:");
  $leadership = $character->getLeadership();
  $leadershipbonus = $bonuses->pointAndLimitBonus("leadership");
  if ($leadershipbonus['always'] != 0) {
    $leadership += $leadershipbonus['always'];
  }
  $xpos = $aapcolumntwo;
  drawwhiteboxes($pdf, $xpos, 374, $leadership);

  pdf_set_text_pos($pdf, $aapcolumnone, 362);
  pdf_show($pdf, "Stunts:");
  pdf_set_text_pos($pdf, $aapcolumntwo, 362);
  pdf_show($pdf, $character->getCoolPoints());
  
  pdf_set_text_pos($pdf, $aapcolumnone, 350);
  pdf_show($pdf, "Stress:");
  drawwhiteboxes($pdf, $xpos, 350, $character->getFearLimit());

  pdf_set_text_pos($pdf, $aapcolumnone, 338);
  pdf_show($pdf, "Psycho:");
  drawwhiteboxes($pdf, $xpos, 338, $character->getPsychoLimit());
  $xpos = $aapcolumntwo-2;
  for($i = 1; $i <= $character->getPsychoPoints(); $i ++) {
	  pdf_set_text_pos($pdf, $xpos, 338);
	  pdf_show($pdf, "X");
	  $xpos += 8;
  }
  $xpos = $aapcolumntwo;

  pdf_set_text_pos($pdf, $aapcolumnone, 326);
  pdf_show($pdf, "Experience:");
  pdf_set_text_pos($pdf, $aapcolumntwo, 326);
  pdf_show($pdf, $character->getUnusedXp());


  pdf_set_text_pos($pdf, $aapcolumnfour, 374);
  pdf_show($pdf, "Carry Capacity:");
  pdf_set_text_pos($pdf, $aapcolumnfive, 374);
  pdf_show($pdf, $character->getCarryCapacity());
  pdf_set_text_pos($pdf, $aapcolumnsix, 374);
  $carrybonus = $bonuses->carryCapacity();
  print_pdf_bonus($pdf, $carrybonus);

  pdf_set_text_pos($pdf, $aapcolumnfour, 362);
  pdf_show($pdf, "Combat Load:");
  pdf_set_text_pos($pdf, $aapcolumnfive, 362);
  pdf_show($pdf, $character->getCombatLoad());
  pdf_set_text_pos($pdf, $aapcolumnsix, 362);
  $combatbonus = $bonuses->combatLoad();
  print_pdf_bonus($pdf, $combatbonus);
  
  //pdf_set_text_pos($pdf, $aapcolumnfour, 350);
  //pdf_show($pdf, "Stress Limit:");
  //pdf_set_text_pos($pdf, $aapcolumnfive, 350);
  //pdf_show($pdf, $character->getFearLimit());
  //pdf_set_text_pos($pdf, $aapcolumnsix, 350);
  //$fearlimitbonus = $bonuses->fearLimit();
  //print_pdf_bonus($pdf, $fearlimitbonus);

  //pdf_set_text_pos($pdf, $aapcolumnfour, 338);
  //pdf_show($pdf, "Psycho Limit:");
  //pdf_set_text_pos($pdf, $aapcolumnfive, 338);
  //pdf_show($pdf, $character->getPsychoLimit());
  //pdf_set_text_pos($pdf, $aapcolumnsix, 338);
  //$psycholimitbonus = $bonuses->psychoLimit();
  //print_pdf_bonus($pdf, $psycholimitbonus);



  //pdf_set_text_pos($pdf, $aapcolumnfour, 302);
  //pdf_show($pdf, "Exhaustion Limit:");
  //pdf_set_text_pos($pdf, $aapcolumnfive, 302);
  //pdf_show($pdf, $character->getExhaustionLimit());
  //pdf_set_text_pos($pdf, $aapcolumnsix, 302);
  //$exhaustionlimitbonus = $bonuses->exhaustionLimit();
  //print_pdf_bonus($pdf, $exhaustionlimitbonus);

  // Missions
  fontbold($font, $pdf);
  $missionheight = 296;
  pdf_set_text_pos($pdf, 50, $missionheight);
  pdf_show($pdf, "Missions");
  fontregular($font, $pdf);
  $missionarray = $character->getMissionsShort();
  $missionheight -= 12;
  foreach ( $missionarray as $mission ) {
    pdf_set_text_pos($pdf, 50, $missionheight);
    pdf_show($pdf, $mission['mission_name']);
    pdf_set_text_pos($pdf, 90, $missionheight);
    pdf_show($pdf, $mission['text']);
    $missionheight -= 12;
  }
  
  //Middle column
  //sort expertise
  $weapons = array();
  $terrain = array();
  $languages = array();
  $expertiseother = array();
  foreach ( $characterController->getExpertiseNotOnSkills($character) as $exp ) {
	  if ($exp->getExpertiseGroupId() == 6) {
		  $weapons[] = $exp;
	  } else if ($exp->getExpertiseGroupId() == 7) {
		  $terrain[] = $exp;
	  } else if ($exp->getExpertiseGroupId() == 14) {
		  $languages[] = $exp;
	  } else {
		  $expertiseother[] = $exp;
	  }
  }
  
  // Weapons
  fontbold($font, $pdf);
  $weaponsheight = 772;
  pdf_set_text_pos($pdf, 220, $weaponsheight);
  pdf_show($pdf, "Weapons");
  fontregular($font, $pdf);
  $weaponsheight -= 12;
  if ($weapons) {
	drawbox($pdf, $weapons, 220, $weaponsheight, 32, 4);
  }
  
    // Expertise
  fontbold($font, $pdf);
  $expertiseheight = 639;
  pdf_set_text_pos($pdf, 220, $expertiseheight);
  pdf_show($pdf, "Other Expertise");
  fontregular($font, $pdf);
  $expertiseheight -= 12;
  if ($expertiseother) {
	drawbox($pdf, $expertiseother, 220, $expertiseheight, 32, 4);
  }

  // Language
  fontbold($font, $pdf);
  $languageheight = 514;
  pdf_set_text_pos($pdf, 220, 514);
  pdf_show($pdf, "Language");
  fontregular($font, $pdf);
  $languageheight -= 12;
  if ($languages) {
	drawbox($pdf, $languages, 220, $languageheight, 32);
  }

  // Terrain
  fontbold($font, $pdf);
  $terrainheight = 474;
  pdf_set_text_pos($pdf, 220, $terrainheight);
  pdf_show($pdf, "Terrain");
  fontregular($font, $pdf);
  $terrainheight -= 12;
  if ($terrain) {
	drawbox($pdf, $terrain, 220, $terrainheight, 32, 2);
  }

  // Traits
  fontbold($font, $pdf);
  $traitsheight = 296;
  pdf_set_text_pos($pdf, 170, $traitsheight);
  pdf_show($pdf, "Traits");
  fontregular($font, $pdf);
  $traitarray = $character->getTraits();
  $traitsheight -= 12;
  foreach ( $traitarray as $trait ) {
    pdf_set_text_pos($pdf, 170, $traitsheight);
    pdf_show($pdf, $trait['trait_name']);
    $traitsheight -= 12;
  }

  // Advantages
  fontbold($font, $pdf);
  $advheight = 200;
  if ($traitsheight - 12 < $advheight) {
    $advheight = $traitsheight - 12;
  }

  pdf_set_text_pos($pdf, 170, $advheight);
  pdf_show($pdf, "Advantages");
  fontregular($font, $pdf);
  $allAdvantages = $character->getAdvantagesAll();
  $advheight -= 12;
  foreach ( $allAdvantages as $adv ) {
    PDF_show_boxed($pdf, $adv->getName(), 170, $advheight, 105, 12, 'L', '');
    $advheight -= 12;
  }

  // Disadvantages
  fontbold($font, $pdf);
  $disadvheight = 296;
  pdf_set_text_pos($pdf, 280, $disadvheight);
  pdf_show($pdf, "Disadvantages");
  fontregular($font, $pdf);
  $allDisadvantages = $character->getDisadvantagesAll();
  $disadvheight -= 12;
  foreach ( $allDisadvantages as $disadvantage ) {
    PDF_show_boxed($pdf, $disadvantage->getName(), 280, $disadvheight, 95, 12, 'L', '');
    $disadvheight -= 12;
  }

  // Right column
  // Skills
  fontbold($font, $pdf);
  $skillsheight = 772;
  $skillsxpos = 390;
  $expxpos = $skillsxpos + 10;
  $levelxpos = 510;
  pdf_set_text_pos($pdf, $skillsxpos, $skillsheight);
  pdf_show($pdf, "Skills");
  pdf_set_text_pos($pdf, 500, $skillsheight);
  pdf_show($pdf, "Level");
  fontregular($font, $pdf);
  $skillarray = $character->getWeaponSkills();
  $skillsheight -= 12;
  foreach ( $skillarray as $skill ) {
    pdf_set_text_pos($pdf, $skillsxpos, $skillsheight);
    pdf_show($pdf, $skill['name']);

    pdf_set_text_pos($pdf, $levelxpos, $skillsheight);
    pdf_show($pdf, $skill['value']);
    $skillsheight -= 12;
    $skillexpertise = $characterController->getExpertiseOnSkill($character, $skill['id']);
	if ($skillexpertise) {
		pdf_set_text_pos($pdf, $skillsxpos, $skillsheight);
		pdf_show($pdf, ' >');
		$skillsheight = drawbox($pdf, $skillexpertise, $expxpos, $skillsheight, 25);
	}
  }
  $skillsheight -= 12;
  $skillarray = $character->getPhysicalSkills();
  foreach ( $skillarray as $skill ) {
    pdf_set_text_pos($pdf, $skillsxpos, $skillsheight);
    pdf_show($pdf, $skill['name']);

    pdf_set_text_pos($pdf, $levelxpos, $skillsheight);
    $skillexpertise = $characterController->getExpertiseOnSkill($character, $skill['id']);
    pdf_show($pdf, $skill['value']);
    $skillsheight -= 12;
    $skillexpertise = $characterController->getExpertiseOnSkill($character, $skill['id']);
    if ($skillexpertise) {
		pdf_set_text_pos($pdf, $skillsxpos, $skillsheight);
		pdf_show($pdf, ' >');
		$skillsheight = drawbox($pdf, $skillexpertise, $expxpos, $skillsheight, 25);
	}
  }

  $skillsheight -= 12;
  $skillarray = $character->getVehiclesSkills();
  foreach ( $skillarray as $skill ) {
    pdf_set_text_pos($pdf, $skillsxpos, $skillsheight);
    pdf_show($pdf, $skill['name']);

    pdf_set_text_pos($pdf, $levelxpos, $skillsheight);
    pdf_show($pdf, $skill['value']);
    $skillsheight -= 12;
    $skillexpertise = $characterController->getExpertiseOnSkill($character, $skill['id']);
    if ($skillexpertise) {
		pdf_set_text_pos($pdf, $skillsxpos, $skillsheight);
		pdf_show($pdf, ' >');
		$skillsheight = drawbox($pdf, $skillexpertise, $expxpos, $skillsheight, 25);
	}

  }
  $skillsheight -= 12;
  $skillarray = array_merge($character->getTechnicalSkills(), $character->getMilitarySkills(), $character->getOtherSkills());
  foreach ( $skillarray as $skill ) {
    pdf_set_text_pos($pdf, $skillsxpos, $skillsheight);
    pdf_show($pdf, $skill['name']);

    pdf_set_text_pos($pdf, $levelxpos, $skillsheight);
    pdf_show($pdf, $skill['value']);
    $skillsheight -= 12;
    
    $skillexpertise = $characterController->getExpertiseOnSkill($character, $skill['id']);
    if ($skillexpertise) {
		pdf_set_text_pos($pdf, $skillsxpos, $skillsheight);
		pdf_show($pdf, ' >');
		$skillsheight = drawbox($pdf, $skillexpertise, $expxpos, $skillsheight, 25);
	}
  }
  
  pdf_set_text_pos($pdf, 500, 810);
  pdf_show($pdf, "www.uscm.se");

  pdf_end_page($pdf);
  pdf_close($pdf);

  $buf = pdf_get_buffer($pdf);
  $len = strlen($buf);

  header("Content-type: application/pdf");
  header("Content-Length: $len");
  header("Content-Disposition: inline; filename=" . $character->getSurname() . ".pdf");
  print $buf;
}
