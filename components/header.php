<a href="#main" class="screen-reader-only">Skip to content</a>

<header class="header">
  <div class="logo">
    <div class="image">
      <?php echo file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$url_root}/assets/logo/uscm-blip-logo-animate.svg") ?>
    </div>
    <div class="p-10 center">
      Roleplaying game partially based on the Alien movies. The players are members of one of the platoons in the 4th US Colonial Marine brigade.
    </div>
  </div>

  <nav class="nav">
    <?php
      $urlParam = $_GET["url"] ?? "";
    ?>
    <ul>
      <li>
        <a href="index.php?url=news/news.php" <?php echo !$urlParam || $urlParam == "news/news.php" ? "aria-current='page'" : ""?>>
          News
        </a>
      </li>
      <li>
        <a href="index.php?url=about/about.php" <?php echo $urlParam == "about/about.php" ? "aria-current='page'" : ""?>>
          About
        </a>
      </li>
      <?php if ($_SESSION['level']==3): ?>
        <li>
          <a href="index.php?url=player/edit.php" <?php echo $urlParam == "player/edit.php" ? "aria-current='page'" : ""?>>
            Players
          </a>
        </li>
      <?php endif ?>
      <li>
        <a href="index.php?url=characters/list.php" <?php echo $urlParam == "characters/list.php" ? "aria-current='page'" : ""?>>
          Characters
        </a>
      </li>
      <li>
        <a href="index.php?url=missions/list.php" <?php echo $urlParam == "missions/list.php" ? "aria-current='page'" : ""?>>
          Missions
        </a>
      </li>
      <li>
        <a href="index.php?url=fame/list.php" <?php echo $urlParam == "fame/list.php" ? "aria-current='page'" : ""?>>
          Hall of Fame
        </a>
      </li>
      <li>
        <a href="index.php?url=statistics/index.php" <?php echo $urlParam == "statistics/index.php" ? "aria-current='page'" : ""?>>
          Statistics
        </a>
      </li>
      <li>
        <a href="https://discord.gg/nEp7kwd4h7" target="_blank">
          Discord
          <svg aria-hidden="true">
            <use href="assets/icons/discord-logo.svg#discord-logo"></use>
          </svg>
        </a>
      </li>
      <?php if ($_SESSION['inloggad']==1): ?>
        <li>
          <a href="index.php?url=player/details.php&player=<?php echo $_SESSION['user_id']; ?>" <?php echo $urlParam  == "player/details.php" && $_GET["player"] == $_SESSION['user_id'] ? "aria-current='page'" : ""?>>
            My page
            <svg aria-hidden="true">
              <use href="assets/icons/user-circle.svg#user-circle"></use>
            </svg>
          </a>
        </li>
        <li>
          <a href="actions/auth.php?alt=logout">
            Log Out
            <svg aria-hidden="true">
              <use href="assets/icons/sign-out.svg#sign-out"></use>
            </svg>
          </a>
        </li>
      <?php else:
        $redirect = $urlParam ? "&redirect={$urlParam}" : "";
        ?>
        <li>
          <a href="index.php?url=auth/login.php&alt=login<?php echo $redirect; ?>">
            Log In
            <svg aria-hidden="true">
              <use href="assets/icons/sign-in.svg#sign-in"></use>
            </svg>
          </a>
        </li>
      <?php endif ?>
    </ul>
  </nav>
</header>
