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

  $user_id = $_SESSION['user_id'];
  if (isset($user_id)) :

    require_once('db.php');

    $user = $mysqli->execute_query('SELECT * FROM users WHERE id = ?', [$user_id])->fetch_assoc();
  ?>
    <article>post</article>
    <form action="comment" method="post">
      <figure>
        <figcaption><?= $user['name'] ?></figcaption>
      </figure>
    </form>
  <?php else : ?>
    <a href="auth.php">sign in or create account</a>
  <?php endif ?>
</body>

</html>