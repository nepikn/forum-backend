<?php

function get_username($mysqli, $id)
{
  return $mysqli->execute_query('SELECT name FROM users WHERE id = ?', [$id])->fetch_column();
}
