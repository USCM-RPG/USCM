<?php
  include("session.php");
  include("functions.php");
  include("components/security-headers.php");
?>
  <!DOCTYPE html>
  <html lang="sv">
    <head>
      <?php
      include("components/meta.php");
      ?>
    </head>
    <body>
    <div class="galaxy <?php echo !($_GET["url"] ?? "") ? "animate" : "" ?>"></div>

    <div class="wrapper">
      <?php
        include("components/header.php");
      ?>

      <main class="main" id="main">
        <?php
        if(isset($_GET['url'])){
          // To make sure the file loaded is in the local file system and not a remote url
          $pages = recursive_dirlist('./pages');
          if(in_array('/'.$_GET['url'], $pages)) {
            include('pages/'.$_GET['url']);
          }
        }
        else {
          include('components/404.php');
        }
        ?>
      </main>

      <?php
        include("components/footer.php");
      ?>
    </div>
    </body>
  </html>
