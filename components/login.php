<h1 class="heading heading-h1">Log in</h1>

<form class="form" action="actions/auth.php?alt=login<?php echo $redirect;?>" method="post">
  <label for="anvandarnamn">
    Username
    <input type="text" id="anvandarnamn" name="anvandarnamn" autocomplete="username">
  </label>

  <label for="losenord">
    Password
    <input type="password" id="losenord" name="losenord" autocomplete="current-password">
  </label>

  <input type="hidden" name="rpg" value="uscm_" />

  <button class="button" type="submit">Log in</button>
</form>
