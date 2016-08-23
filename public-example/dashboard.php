<?php
require 'init.php';
require 'auth.php';
$user = $comPHPartment->authorize();

/**
 * Retrieves the ten most recent items from your Pocket list.
 * @see https://getpocket.com/developer/docs/v3/retrieve for a list of parameters you can use in the array 
 * passed to the retrieve method.
 */
$content  = new ComPHPartment\Contents($comPHPartment->client);
$contents = $content->retrieve(['count' => 10]);

$list = $contents->list;
echo count(get_object_vars($list)) . " items found:<hr>";

foreach ($list as $itemID => $item) {
    echo '<a target="_blank" href="' . $item->given_url . '">' . $item->given_title . '</a><br>';
}
