<?php

class Bonus {
  private $db = NULL;
  private $characterId;

  function __construct($characterId) {
    $this->characterId = $characterId;
    $this->db = getDatabaseConnection();
  }

  public function attributeBonus($modifiertype, $attribute) {
    return $this->columnBonus('attribute_names', $modifiertype, $attribute);
  }

  function pointAndLimitBonus($bonustype) {
    return $this->columnBonus($bonustype, 'modifier_basic_value');
  }

  function carryCapacity() {
    return $this->pointAndLimitBonus('carrycapacity');
  }

  function combatLoad() {
    return $this->pointAndLimitBonus('combatlod');
  }

  function psychoPoints() {
    return $this->pointAndLimitBonus('psychopoints');
  }
  function psychoLimit() {
    return $this->pointAndLimitBonus('psycholimit');
  }
  function fearPoints() {
    return $this->pointAndLimitBonus('fearpoints');
  }
  function fearLimit() {
    return $this->pointAndLimitBonus('fearlimit');
  }
  function exhaustionPoints() {
    return $this->pointAndLimitBonus('exhaustionpoints');
  }
  function exhaustionLimit() {
    return $this->pointAndLimitBonus('exhaustionlimit');
  }

  /**
   * Sums the advantage/disadvantage/trait modifiers for a table_point_name
   * (optionally narrowed to one column_id), split into always-active vs.
   * conditional ("sometimes") bonuses.
   */
  public function columnBonus($tablePointName, $modifierColumn, $columnId = null) {
    $bonus = Array ('always' => 0,'sometimes' => Array ()
    );
    $sources = Array (
      Array ('uscm_advantages', 'advantage_name_id', 'advid'),
      Array ('uscm_disadvantages', 'disadvantage_name_id', 'disadvid'),
      Array ('uscm_traits', 'trait_name_id', 'traitid'),
    );
    $columnFilter = $columnId !== null ? "column_id = :columnid AND $modifierColumn IS NOT NULL AND " : "";
    foreach ( $sources as $source ) {
      list($table, $entityKey, $advdisKey) = $source;
      $sql = "SELECT $modifierColumn, value_always_active
              FROM uscm_advdisadv_bonus advdis
              INNER JOIN $table a ON a.$entityKey = advdis.$advdisKey
              WHERE {$columnFilter}table_point_name = :tablepointname AND a.character_id = :cid";
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':cid', $this->characterId, PDO::PARAM_INT);
      $stmt->bindValue(':tablepointname', $tablePointName, PDO::PARAM_STR);
      if ($columnId !== null) {
        $stmt->bindValue(':columnid', $columnId, PDO::PARAM_INT);
      }
      $stmt->execute();
      while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        if ($row['value_always_active'] == 1) {
          $bonus['always'] = $bonus['always'] + $row[$modifierColumn];
        } else {
          $bonus['sometimes'][] = $row[$modifierColumn];
        }
      }
    }
    return $bonus;
  }
}
