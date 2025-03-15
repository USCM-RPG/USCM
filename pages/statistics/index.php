<link rel="stylesheet" href="<?php echo $url_root ?>/assets/charts.css">
<style>
  .charts-css {
    margin-top: 20px;

    th {
      font-weight: normal;
      white-space: nowrap;
    }

    .rotate {
      rotate: -67.5deg;
    }

    &.column {
      --labels-size: 2.5rem;
    }
  }
</style>

<h1 class="heading heading-h1">Statistics</h1>

<table class="charts-css column show-heading show-labels labels-align-block-center data-outside show-data-on-hover show-data-axes">
  <caption class="colorfont">
    Missions per year
    <hr class="line">
  </caption>
  <thead>
  <tr>
    <th scope="col">Year</th>
    <th scope="col">Missions</th>
  </tr>
  </thead>
  <tbody>
  <?php
  $sql = "select DATE_FORMAT(mn.date, '%Y') as year, count(id) as missions from uscm_mission_names mn where id>3 group by year order by year asc";
  $dbReference = getDatabaseConnection();
  $stmt = $dbReference->query($sql);
  $stmt->execute();
  $start=0;
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <tr>
      <th scope="row" class="rotate"><?php echo $row['year'] ?></th>
      <td style="--start: <?php echo $start / 50 ?>; --end: <?php echo $row['missions'] / 50 ?>">
        <span class="data"><?php echo $row['missions'] ?></span>
      </td>
    </tr>
    <?php
    $start=$row['missions'];
  }
  ?>
  </tbody>
</table>
