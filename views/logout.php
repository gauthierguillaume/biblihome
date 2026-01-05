<?php
include($_SERVER['DOCUMENT_ROOT'] . '/host.php');

unset($_SESSION['user']);
header("Location: /index.php");
exit;
