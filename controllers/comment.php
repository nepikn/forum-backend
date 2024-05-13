<?php
require_once '../db/conn.php';
require_once '../util/res.php';

function get($req) {
  global $mysqli;
  ['page' => $page, 'commentPerPage' => $commentPerPage] = $req['queries'];

  $result = $mysqli->query(sprintf(
    'SELECT
        SQL_CALC_FOUND_ROWS *
      FROM
        comments AS c
        INNER JOIN (
          SELECT
            id AS user_id,
            name AS commentator
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

  printJson($comments);
}
