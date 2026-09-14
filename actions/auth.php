<?php
    include("../session.php");
    include("../functions.php");

    /*
     * Levels:
     * 0: Logged out
     * 1: Registered user
     * 2: Game master
     * 3: Site admin
     */
    function login() {
      if (empty($_POST['anvandarnamn']) || empty($_POST['losenord'])) {
        logout();
      }

      $db = getDatabaseConnection();
      $query = "SELECT Users.id, emailadress, platoon_id, Admins.userid AS Admin, GMs.userid AS GM,
                    logintime, count(Users.id) AS howmany
                    FROM Users
                    LEFT JOIN Admins ON Admins.userid=Users.id
                    LEFT JOIN GMs ON GMs.userid=Users.id AND GMs.active=1
                    WHERE emailadress=:userName
                    AND `password`=password(:password)
                    GROUP BY Users.id";
      $stmt = $db->prepare($query);
      $stmt->bindValue(':userName', $_POST['anvandarnamn'], PDO::PARAM_STR);
      $stmt->bindValue(':password', $_POST['losenord'], PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!is_null($row['howmany'] ) && $row['howmany'] == 1) {
        $userinfo = $row;

        $userlevel = 1;
        if ($userinfo['Admin']) {
          $userlevel = 3;
        } elseif ($userinfo['GM']) {
          $userlevel = 2;
        }

        $_SESSION['anvandarnamn'] = $_POST['anvandarnamn'];
        $_SESSION['inloggad'] = 1;
        $_SESSION['level'] = $userlevel;
        $_SESSION['table_prefix'] = $_POST['rpg'];
        $_SESSION['user_id'] = $userinfo['id'];
        $_SESSION['platoon_id'] = $userinfo['platoon_id'];
        $db->exec("UPDATE Users SET lastlogintime = '{$userinfo['logintime']}' WHERE id = {$userinfo['id']}");
        $db->exec("UPDATE Users SET logintime = NOW() WHERE id = {$userinfo['id']}");
        return 1;
      } elseif (!is_null($row['howmany'] ) && $row['howmany'] > 1) {
        logout();
      } else {
        logout();
      }
    }

    function logout() {
      $_SESSION['inloggad'] = 0;
      $_SESSION['level'] = "0";
      unset($_SESSION['anvandarnamn']);
      unset($_SESSION['table_prefix']);
      unset($_SESSION['user_id']);
      unset($_SESSION['platoon_id']);
      session_unset();
      session_destroy();
      return 0;
    }

    if (isset($_GET['alt'])) {
      $alt = $_GET['alt'];
      if ($alt == "logout") {
        logout();
      } elseif ($alt == "login") {
        login();
      }
    }

    $redirect = isset($_GET['redirect']) ? "?url={$_GET["redirect"]}" : "";
    header("Location: {$url_root}/index.php{$redirect}");
?>
