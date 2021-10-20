<?php

header('Content-type: application/json');

require_once ROOT."controllers".DS."User.php";
require_once ROOT."controllers".DS."UserManager.php";

$um = UserManager::getInstance($db);
$usr1Id = intval($_GET['usr1Id']);
$usr2Id = intval($_GET['usr2Id']);

if($usr1Id != null && $usr2Id != null) {
    $um->addFriendship($usr1Id,$usr2Id);
    echo '{"result":true}';
} else {
    echo '{"result":false}';
}