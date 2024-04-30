<?php
require_once('./db/conn.php');
session_start();
// unset($_SESSION['user']);
$user = ($_SESSION['user'] ??= ['err' => false]);
// $user_id = &$user['id'];
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
    <?php if (empty($user['id'])) : ?>
      <a href="auth.php">sign in or create account</a>
    <?php else : ?>
      <form action="./handler/logout.php" method="post"><button>Log out</button></form>
    <?php endif; ?>
  </nav>

  <?php
  foreach ($mysqli->query('SELECT * FROM comments') as $comment) {
    $texts = [$mysqli
      ->query(sprintf('SELECT name FROM users WHERE id = %d', $comment['user_id']))
      ->fetch_column(), $comment['content']];

    array_walk($texts, fn (&$s) => $s = htmlspecialchars($s));
    // array_map(fn (&$s) => htmlspecialchars($s), $texts);
    // echo var_export($texts);

    printf(
      '<article>
    <figure>
      <figcaption>%s</figcaption>
    </figure>
    <p>%s</p>
  </article>',
      ...$texts
    );
  }

  if (empty($user['id'])) exit
  ?>

  <div>
    <figure>
      <figcaption><?= $user['name'] ?></figcaption>
    </figure>
    <form action="./handler/comment.php" method="post">
      <textarea required name="content" id="" cols="30" rows="10"></textarea>
      <button>comment</button>
    </form>
  </div>
</body>

</html>