<?php
require 'init.php';
require 'auth.php';

if ($_POST) {
    $user = $comPHPartment->authorize();

    $content  = new ComPHPartment\Contents($comPHPartment->client);
    $contents = $content->create($_POST['url'], $_POST['title']);
} else {
    echo '<form method="post">
        <label for="url">URL: </label>
        <input id="url" type="text" name="url"><br>
        <label for="title">Title: </label>
        <input id="title" type="text" name="title"><br>
        <input type="submit">
    </form>';
}
