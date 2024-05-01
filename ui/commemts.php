<?php
function printComments($template, $currentUserId)
{
  global $mysqli;

  foreach ($mysqli->query('SELECT * FROM comments ORDER BY id DESC') as $comment) {
    $commentUserId = $comment['user_id'];
    // $menu = $commentUserId != $currentUserId ? '' :
    //   '<form>';
    $userInputs = [
      getUsername($mysqli, $commentUserId),
      $comment['content']
    ];

    vprintf($template, array_map('htmlspecialchars', $userInputs));

    // array_walk($strings, fn (&$s) => $s = htmlspecialchars($s));
    // echo var_export($strings);
  }
}

function UserMenu()
{
  return '';
}
