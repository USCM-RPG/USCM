<?php
require_once ("config.php");
require_once ("classes/lazy_loader.php");
require_once ("classes/db_entity.php");
require_once ("classes/attribute.php");
require_once ("classes/advantage.php");
require_once ("classes/bonus.php");
require_once ("classes/certificate.php");
require_once ("classes/character.php");
require_once ("classes/disadvantage.php");
require_once ("classes/expertise.php");
require_once ("classes/news.php");
require_once ("classes/medal.php");
require_once ("classes/mission.php");
require_once ("classes/player.php");
require_once ("classes/platoon.php");
require_once ("classes/psychodisadv.php");
require_once ("classes/rank.php");
require_once ("classes/skill.php");
require_once ("classes/specialty.php");
require_once ("classes/trait.php");
require_once ("classes/tags.php");
require_once ("controllers/character.php");
require_once ("controllers/expertise.php");
require_once ("controllers/news.php");
require_once ("controllers/medal.php");
require_once ("controllers/mission.php");
require_once ("controllers/platoon.php");
require_once ("controllers/player.php");
require_once ("controllers/rank.php");
require_once ("controllers/tag.php");

$db_connection = NULL;

/**
 * Returns a new or existing database connection
 *
 * @return PDO
 */
function getDatabaseConnection() {
  global $db_connection;
  if ($db_connection != NULL) {
    return $db_connection;
  } else {
    $db_connection = new PDO(
        'mysql:host=' . $GLOBALS['db_host'] . ';dbname=' . $GLOBALS['db_database'] . ';charset=utf8',
        $GLOBALS['db_user'], $GLOBALS['db_password']);
    return $db_connection;
  }
}

function attribute2visible($attributearray) {
  $attribarray = array ();
  foreach ( $attributearray as $id => $key ) {
    switch ($key['attribute_name']) {
      case ("Charisma") :
        switch ($key['value']) {
          case (1) :
      $astrings = array(
        '',
        'Loner',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (2) :
            //$attribarray[] = "Ugly";
            break;
          case (3) :
            //$attribarray[] = "Average looks";
            break;
          case (4) :
      $astrings = array(
        'Good looking',
        'Nice Person',
        'Leader',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (5) :
      $astrings = array(
        'Good leader',
        'Leader',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
        }
        break;
      case ("Dexterity") :
        switch ($key['value']) {
          case (1) :
      $astrings = array(
        'Walking board',
        'Wooden leg',
        'Clumsy',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (2) :
      $astrings = array(
        '',
        'Stiff',
        'Clumsy',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (3) :
            //$attribarray[] = "Agile";
            break;
          case (4) :
      $astrings = array(
        '',
        'Agile',
        'Quick-footed',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (5) :
      $astrings = array(
        'Acrobat',
        'Quick-footed',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
        }
        break;
      case ("Endurance") :
        switch ($key['value']) {
          case (1) :
      $astrings = array(
        'Lazy',
        'Bad fitness',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (2) :
      $astrings = array(
        '',
        'Bad fitness',
        'Average fitness',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (3) :
      $astrings = array(
        '',
        'Good fitness',
        'Average fitness',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (4) :
            $attribarray[] = "Good fitness";
            break;
          case (5) :
      $astrings = array(
        'Good fitness',
        'Extreme fitness',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
        }
        break;
      case ("Perception") :
        switch ($key['value']) {
          case (1) :
      $astrings = array(
        '',
        'Nearly blind',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (2) :
      $astrings = array(
        '',
        'Near sighted',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (3) :
            //$attribarray[] = "Normal sight";
            break;
          case (4) :
      $astrings = array(
        '',
        'Good sight',
        'Attentive',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (5) :
      $astrings = array(
        '',
        'An eye for details',
        'Attentive',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
        }
        break;
      case ("Psionics") :
        switch ($key['value']) {
          case (1) :
            $attribarray[] = "";
            break;
          case (2) :
      $astrings = array(
        '',
        'Something is strange',
        'Not like others',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (3) :
      $astrings = array(
        '',
        'Something is strange',
        'Not like others',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (4) :
      $astrings = array(
        '',
        'Something is strange',
        'Not like others',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (5) :
      $astrings = array(
        '',
        'Something is strange',
        'Not like others',
        'The force is strong with this one',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
        }
        break;
      case ("Psyche") :
        switch ($key['value']) {
          case (1) :
      $astrings = array(
        'Coward',
        'Nervous',
        'Seems a bit unstable',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (2) :
      $astrings = array(
        '',
        'Nervous',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (3) :
      $astrings = array(
        '',
        'Calm',
        'Reliable',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (4) :
      $astrings = array(
        'Calm',
        'Reliable',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (5) :
      $astrings = array(
        'Reliable',
        'Brave',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
        }
        break;
      case ("Reaction") :
        switch ($key['value']) {
          case (1) :
            //$attribarray[] = "Slow as a snail";
            break;
          case (2) :
            //$attribarray[] = "Slow";
            break;
          case (3) :
            //$attribarray[] = "Fast";
            break;
          case (4) :
            //$attribarray[] = "Very fast";
            break;
          case (5) :
      $astrings = array(
        'Lightning reflexes',
        'Phantom reflexes',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
        }
        break;
      case ("Strength") :
        switch ($key['value']) {
          case (1) :
      $astrings = array(
        'Weak',
        'Weakling',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (2) :
      $astrings = array(
        'Weak',
        '',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (3) :
      $astrings = array(
        'Average strength',
        '',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (4) :
      $astrings = array(
        '',
        'Strong',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (5) :
      $astrings = array(
        'Very strong',
        'Hercules',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
        }
        break;
      case ("Toughness") :
        switch ($key['value']) {
          case (1) :
      $astrings = array(
        'Fragile',
        'Easily bruised',
      );
      $attribarray[] = $astrings[array_rand($astrings)];
            break;
          case (2) :
            $attribarray[] = "Easily bruised";
            break;
          case (3) :
            //$attribarray[] = "Tough";
            break;
          case (4) :
            //$attribarray[] = "Very tough";
            break;
          case (5) :
            $attribarray[] = "Tough like a Sergeant";
        }
        break;
    }
  }
  return $attribarray;
}

function print_pdf_bonus($pdf, $bonusarray) {
  $totalBonus = "";
  if ($bonusarray['always'] != 0) {
    if ($bonusarray['always'] > 0) {
      $bonussign = "+";
    } else {
      $bonussign = "";
    }
    $totalBonus = $bonussign . $bonusarray['always'];
    //pdf_show($pdf, $bonussign . $bonusarray['always'] . " ");
  }
  if (is_array($bonusarray['sometimes'])) {
    foreach ( $bonusarray['sometimes'] as $bonus ) {
      if ($bonus > 0) {
        $bonussign = "+";
      } else {
        $bonussign = "";
      }
      $totalBonus .= " (" . $bonussign . $bonus . ")";
      //pdf_show($pdf, " (" . $bonussign . $bonus . ") ");
    }
  }
  if ($totalBonus != "") {
    pdf_show($pdf, $totalBonus);
  }
}

function print_text_without_br($text) {
  return strtr($text, array ("<br/>" => ""
  ));
}
?>
