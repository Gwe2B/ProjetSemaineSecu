<?php

header('Content-type: application/json');

require_once ROOT."controllers".DS."Image.php";
require_once ROOT."controllers".DS."ImageManager.php";

$mngr = ImageManager::getInstance($db);
$img = $mngr->getById(intval($_GET['imgId']));

if($img != null && $img->getUser_Id() === $user->getId()) {
    $mngr->removeImage($img);
    echo '{"result":true}';
} else {
    echo '{"result":false}';
}