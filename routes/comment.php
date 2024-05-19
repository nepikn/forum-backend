<?php
require_once '../controllers/comment.php';
require_once '../util/router.php';

// $comment_controller = new CommentController();
$router = new Router('/api/comment', new CommentController());

$router->post('');
$router->get('');
$router->get('/:id');
$router->put('');
$router->delete('');

// $router->post('', $comment_controller);
// $router->get('');
// $router->get('/:id', $comment_controller);
// $router->put('', $comment_controller);
// $router->delete('', $comment_controller);

function printComment($comment, $byCurrentUser) {
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

function printPagination($page, $pageCount) {
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

function printArrow($atBoundry, $text, $targPage) {
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
