<?php
function printComments($currentUserId)
{
  global $mysqli;
  $comments = $mysqli->query(
    'SELECT
      *
    FROM
      comments AS c
      INNER JOIN users ON c.user_id = users.id
    ORDER BY
      c.id DESC'
  );

  ob_start();
  foreach ($comments as $comment) {
    $commentUserId = $comment['user_id'];

    printComment(
      $comment,
      $commentUserId == $currentUserId
    );
  }
  printf('<section>%s</section>', ob_get_clean());
}

function printComment($comment, $byCurrentUser)
{
  $user_inputs = [$comment['name'], $comment['content']];
  array_walk(
    $user_inputs,
    fn (&$s) => $s = htmlspecialchars($s)
  );
?>
  <article>

    <figure>
      <figcaption><?= $user_inputs[0] ?></figcaption>
    </figure>
    <p><?= $user_inputs[1] ?></p>

    <?php if ($byCurrentUser) : ?>
      <form action="./handler/comment_edit.php" method="post">
        <label for="commentEdit">Edit</label>
        <input type="text" name="content" id="commentEdit">
        <input type="hidden" name="commentId" value="<?= $comment['id'] ?>">
        <button>Save</button>
      </form>

      <form action="../handler/comment_del.php" method="post">
        <button>Delete</button>
      </form>
    <?php endif ?>

  </article>
<?php
}
