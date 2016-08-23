<?php
require 'init.php';

ComPHPartment\ComPHPartment::$redirectURI = 'http://comphpartment/dashboard.php';

$comPHPartment->authenticate();
$_SESSION['token'] = $comPHPartment->token;

require 'auth.php';
