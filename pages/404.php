<?php
include("{$_SERVER['DOCUMENT_ROOT']}{$url_root}/session.php");
include("{$_SERVER['DOCUMENT_ROOT']}{$url_root}/components/security-headers.php");
?>
<!DOCTYPE html>
<html lang="sv">
<head>
  <?php
  include("{$_SERVER['DOCUMENT_ROOT']}{$url_root}/components/meta.php");
  ?>
</head>
<body>
<div class="galaxy"></div>

<div class="wrapper">
  <?php
  include("{$_SERVER['DOCUMENT_ROOT']}{$url_root}/components/header.php");
  ?>

  <main class="main">
    <?php
    include("{$_SERVER['DOCUMENT_ROOT']}{$url_root}/components/404.php");
    ?>
  </main>

  <?php
  include("{$_SERVER['DOCUMENT_ROOT']}{$url_root}/components/footer.php");
  ?>
</div>
</body>
</html>
