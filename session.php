<?php
$cookie_lifetime = 24 * 60 * 60;
ini_set('session.cookie_lifetime', $cookie_lifetime);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', "Strict");
ini_set('session.cookie_secure', 1);
ini_set('session.gc_maxlifetime', $cookie_lifetime);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
ini_set('session.save_path', "/tmp");
session_start();

if (!array_key_exists('last_activity', $_SESSION) || time() - $_SESSION['last_activity'] > 30 * 60) {
  session_regenerate_id(true);
  $_SESSION['last_activity'] = time();
}

if (!array_key_exists('level', $_SESSION)) {
  $_SESSION['level'] = 0;
}
if (!array_key_exists('user_id', $_SESSION)) {
  $_SESSION['user_id'] = -1;
}
if (!array_key_exists('inloggad', $_SESSION)) {
  $_SESSION['inloggad'] = -1;
}

if (!array_key_exists('table_prefix', $_SESSION)) {
  $_SESSION['table_prefix']="uscm_";
}
?>
