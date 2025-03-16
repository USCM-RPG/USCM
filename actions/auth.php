<?php
    include("../session.php");
    include("../functions.php");
    $redirect = isset($_GET['redirect']) ? "?url={$_GET["redirect"]}" : "";
    if (login(1)) {
      header("Location: {$url_root}/index.php{$redirect}");
    } else {
      header("Location: {$url_root}/index.php{$redirect}");
    }
?>
