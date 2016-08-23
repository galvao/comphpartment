<?php
if (!isset($_GET['redirected'])) {
    $comPHPartment->authenticate();
    $_SESSION['token'] = $comPHPartment->token;

    header('Location: ' . 
        ComPHPartment\ComPHPartment::POCKET_BASE_URI . 
        ComPHPartment\ComPHPartment::POCKET_AUTHORIZATION_URI . 
        '?request_token=' . $comPHPartment->token . 
        '&redirect_uri=' . ComPHPartment\ComPHPartment::$redirectURI
    );
}
