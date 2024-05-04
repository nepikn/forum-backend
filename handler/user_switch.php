<?php
session_start();

unset($_SESSION['user']['name']);

header('Location: ../auth.php');
exit;
