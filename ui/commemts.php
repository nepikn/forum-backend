<?php
foreach ($mysqli->query('SELECT * FROM comments ORDER BY id DESC') as $comment) {
  $strings = [
    $mysqli
      ->query(sprintf('SELECT name FROM users WHERE id = %d', $comment['user_id']))
      ->fetch_column(),
    $comment['content']
  ];

  // array_walk($strings, fn (&$s) => $s = htmlspecialchars($s));
  // array_map(fn ($s) => htmlspecialchars($s), $strings);
  // echo var_export($strings);

  printf(
    '<article>
      <figure>
        <figcaption>%s</figcaption>
      </figure>
      <p>%s</p>
    </article>',
    ...array_map('htmlspecialchars', $strings)
  );
}
