<?php
require_once('./db/conn.php');
session_start();
// unset($_SESSION['user']);
$user = ($_SESSION['user'] ??= ['id' => null, 'err' => false,]);
$user_id = $user['id'];
// $user_name = htmlspecialchars($user['name']);
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
  <nav>
    <?php if (!$user_id) : ?>
      <a href="auth.php">sign in or create account</a>

    <?php else :
      $username = getUsername($mysqli, $user['id']);
    ?>
      <form action="./handler/logout.php" method="post"><button>Log out</button></form>
      <form action="./handler/username_edit.php" method="post">
        <input type="text" name="name">
        <button>Edit Name</button>
      </form>
    <?php endif ?>
  </nav>

  <?php
  require('./ui/commemts.php');

  if ($user_id) :
  ?>

    <div>
      <figure>
        <figcaption><?= $username ?></figcaption>
      </figure>
      <form action="./handler/comment_add.php" method="post">
        <textarea required name="content" id="" cols="30" rows="10"></textarea>
        <button>comment</button>
      </form>
    </div>
  <?php endif ?>
</body>

</html>