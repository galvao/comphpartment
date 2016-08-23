<?php
require_once '../autoload.php';
require_once '../vendor/autoload.php';

use ComPHPartment\ComPHPartment;
use ComPHPartment\Contents;

// Pocket suggests the token to be stored on a session.
session_start();

$comPHPartment = new ComPHPartment();
$comPHPartment->token = ((isset($_SESSION['token']) and !is_null($_SESSION['token'])) ? $_SESSION['token'] : null);

/** If the script using init doesn't redefine this, Pocket will redirect to the script itself.
 *  This is particularly useful for the first interaction with the Pocket API.
 */
ComPHPartment::$redirectURI = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '?redirected=true';
