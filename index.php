<?php
require_once('./db/conn.php');
require_once('./util/user.php');
require_once('./ui/nav.php');
require_once('./ui/commemts.php');
require_once('./ui/comment_input.php');
session_start();
// unset($_SESSION['user']);
$user = ($_SESSION['user'] ??= ['id' => null, 'err' => false,]);
$user_id = $user['id'];
$username = getUsername($user_id);
// echo var_export($user) . '<br/>';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forum</title>
  <!-- <script type="module" src=""></script> -->
</head>

<body>
  <?php
  printNav($username);
  printComments($user_id);

  if ($user_id) {
    printInput($username);
  }
  ?>
</body>

</html>