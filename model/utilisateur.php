<?php
require_once ROOT."controllers".DS."User.php";
require_once ROOT."controllers".DS."UserManager.php";

$um = UserManager::getInstance($db);

$pageContent = array(
    'user' => $user,
    'friends'=> $um->getFriendsByUserId($user->getId()),
);

//var_dump($um->getFriendsByUserId($user->getId()));
