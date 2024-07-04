<?php
require_once '../db/query.php';
require_once '../util/controller.php';
require_once '../util/res.php';

class CommentController extends Controller {
  function __construct() {
    $this->db = new Db('comments');
  }

  function post() {
    parent::respond($this->db->insert([
      'user_id' => getSessionUser('id'),
      'content' => $this->req['queries']['content']
    ]));
  }

  function get() {
    @[
      'page' => $page,
      'commentPerPage' => $commentPerPage,
      'cursor' => $cursor
    ] = $this->req['queries'];

    $result = query(
      "SELECT
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
      -- WHERE is_deleted IS NULL -- if soft deleting
      -- AND c.id <= $cursor -- todo: cursor-based pagination
      ORDER BY
        c.id DESC
      LIMIT %d, %d",
      ($page - 1) * $commentPerPage,
      $commentPerPage
    );
    $comments = [];
    foreach ($result as $comment) {
      array_push($comments, $comment);
    }

    parent::respond($comments);
  }

  function put() {
    parent::respond($this->db->update($this->req['queries'], $this->req['args']));
  }

  function delete() {
    parent::respond($this->db->delete($this->req['args']['id']));
  }
}
