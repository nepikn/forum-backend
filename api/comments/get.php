<?php
require_once('../../db/conn.php');

// printComments($_GET['page'], $_GET['commentPerPage']);

// // function printComments($args)
// function printComments($page, $commentPerPage)
// {
// global $mysqli;
// $commentPerPage ??= 5;
['page' => $page, 'commentPerPage' => $commentPerPage] = $_GET;

$result = $mysqli->query(sprintf(
  'SELECT
      SQL_CALC_FOUND_ROWS *
    FROM
      comments AS c
      INNER JOIN (
        SELECT
          id AS user_id,
          name AS user_name
        FROM
          users
      ) AS u ON c.user_id = u.user_id
    WHERE is_deleted IS NULL -- if soft deleting
    ORDER BY
      c.id DESC
    LIMIT %d, %d',
  ($page - 1) * $commentPerPage,
  $commentPerPage
));

$comments = [];
foreach ($result as $comment) {
  array_push($comments, $comment);
}

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Content-Type: application/json; charset=utf-8');

echo json_encode($comments);

// ob_start();
// foreach ($comments as $comment) {
//   $commentUserId = $comment['user_id'];

//   printComment(
//     $comment,
//     $commentUserId == $currentUserId
//   );
// }
// printf('<section>%s</section>', ob_get_clean());
// printPagination(
//   $page,
//   ceil($mysqli->query('SELECT FOUND_ROWS()')->fetch_column() / $commentPerPage)
// );
// }

function printComment($comment, $byCurrentUser)
{
  $user_inputs = [$comment['user_name'], $comment['content']];
  array_walk(
    $user_inputs,
    fn (&$s) => $s = htmlspecialchars($s)
  );
?>
  <article class="card">

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

      <form action="./handler/comment_del.php" method="post">
        <button>Delete</button>
        <input type="hidden" name="commentId" value="<?= $comment['id'] ?>">
      </form>
    <?php endif ?>

  </article>
<?php
}

function printPagination($page, $pageCount)
{
  $prev = max(1, $page - 1);
  $next = min($pageCount, $page + 1);
?>
  <nav>
    <ul>
      <?php
      printArrow($page == 1, 'Prev', $prev);
      printArrow($page == $pageCount, 'Next', $next);
      ?>
    </ul>
  </nav>
<?php
}

function printArrow($atBoundry, $text, $targPage)
{
?>
  <li>
    <?php if ($atBoundry) : ?>
      <span><?= $text ?></span>
    <?php else : ?>
      <a href="?page=<?= $targPage ?>"><?= $text ?></a>
    <?php endif ?>
  </li>
<?php
}
