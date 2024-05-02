<?php

function getUsername($id)
{
  global $mysqli;
  return $id === null ? null : htmlspecialchars($mysqli->execute_query('SELECT name FROM users WHERE id = ?', [$id])->fetch_column());
}
