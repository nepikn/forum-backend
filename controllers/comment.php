<?php
require_once '../db/query.php';
require_once '../util/controller.php';
require_once '../util/res.php';

class CommentController extends Controller {
  function get() {
    ['page' => $page, 'commentPerPage' => $commentPerPage] = $this->req['queries'];

    $result = query(
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
    );
    $comments = [];
    foreach ($result as $comment) {
      array_push($comments, $comment);
    }

    respond($comments);
  }
}
