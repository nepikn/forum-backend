<?php
session_start();

$user = &$_SESSION['user'];
$name = &$user['name'];
$name_checking = empty($user) || empty($name);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forum</title>
</head>

<body>
  <form action="user.php" method="post">
    <label for="name">name</label>
    <?php if ($name_checking) : ?>
      <input type="text" name="name">
    <?php else : ?>
      <input type="text" name="name" id="name" value="<?= $name ?>" readonly>
      <label for="passwd">password</label>
      <input type="password" name="passwd" id="passwd">
    <?php endif ?>
    <button>submit</button>
  </form>
</body>

</html>