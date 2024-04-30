<?php
session_start();

$user = &$_SESSION['user'];
echo var_export($user) . '<br/>';

if (!isset($user) || isset($user['id'])) {
  header('Location: ./index.php');
  exit;
}

$username = &$user['name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forum</title>
</head>

<body>
  <form action="./handler/signin.php" method="post">
    <label for="name">name</label>
    <?php if (empty($username)) : ?>
      <input type="text" name="name" required>
    <?php else : ?>
      <input type="text" name="name" id="name" value="<?= $username ?>" readonly>
      <p>
        <?= $user['db'] === NULL ?
          'please create your password'
          : ($user['err'] ?
            'wrong password ... please retry'
            : 'signing in ... please enter your password')
        ?>
      </p>
      <label for="passwd">password</label>
      <input type="password" name="passwd" id="passwd" required>
    <?php endif ?>
    <button>submit</button>
  </form>
</body>

</html>