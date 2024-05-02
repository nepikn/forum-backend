<?php
function printComments($currentUserId)
{
  global $mysqli;

  $template = getCommentTemp();
  $sql = 'SELECT * FROM comments ORDER BY id DESC';

  ob_start();
  foreach ($mysqli->query($sql) as $comment) {
    $commentUserId = $comment['user_id'];
    // $menu = $commentUserId != $currentUserId ? '' :
    //   '<form>';
    $userInputs = [
      $comment['content']
    ];

    printf(
      $template,
      getUsername($commentUserId),
      ...array_map('htmlspecialchars', $userInputs)
    );

    // array_walk($strings, fn (&$s) => $s = htmlspecialchars($s));
    // echo var_export($strings);
  }

  printf('<section>%s</section>', ob_get_clean());
}

function getCommentTemp()
{
  ob_start();
?>
  <article>
    <figure>
      <figcaption>%s</figcaption>
    </figure>
    <p>%s</p>
  </article>
<?php
  return ob_get_clean();
}

function UserMenu()
{
  return '';
}
