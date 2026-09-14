<?php
  include("session.php");
  include("functions.php");
  include("components/security-headers.php");

  /*
   * Returns all file names under the given directory, recursively, stripped of the base dir.
   * Used for checking validity of includes from GET variables
   */
  function recursive_dirlist($base_dir) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base_dir, FilesystemIterator::SKIP_DOTS));
    $filelist = array();
    foreach ( $files as $file ) {
      if ($file->isFile()) {
        $filelist[] = str_replace($base_dir, '', $file->getPathname());
      }
    }
    return $filelist;
  }
?>
<!DOCTYPE html>
<html lang="en">
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
        } else {
          include('pages/news/news.php');
        }
        ?>
      </main>

      <?php
        include("components/footer.php");
      ?>
    </div>
  </body>
</html>
