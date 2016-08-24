<?php
require 'init.php';

ComPHPartment\ComPHPartment::$redirectURI = 'http://' . $_SERVER['SERVER_NAME'] . '/dashboard.php';

$comPHPartment->authenticate();
$_SESSION['token'] = $comPHPartment->token;

require 'auth.php';
