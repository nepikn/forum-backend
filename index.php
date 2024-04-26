<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forum</title>
</head>

<body>
  <?php
  session_start();

  // unset($_SESSION['user']);
  $user = ($_SESSION['user'] ??= ['err' => false]);
  echo var_export($user);

  if (isset($user['id'])) :
    $name = htmlspecialchars($user['name']);
  ?>
    <nav>
      <form action="logout.php" method="post"><button>Log out</button></form>
    </nav>
    <article>post</article>
    <div>
      <figure>
        <figcaption><?= $name ?></figcaption>
      </figure>
      <form action="comment" method="post">
        <textarea name="" id="" cols="30" rows="10"></textarea>
      </form>
    </div>
  <?php else : ?>
    <a href="auth.php">sign in or create account</a>
  <?php endif ?>
</body>

</html>