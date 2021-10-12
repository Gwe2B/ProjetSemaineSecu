<?php

header('Content-type: application/json');

require_once ROOT."controllers".DS."Galerie.php";
require_once ROOT."controllers".DS."GalerieManager.php";

$mngr = GalerieManager::getInstance($db);
$gal = $mngr->getById(intval($_GET['galId']));

if($gal != null && $gal->getUser_Id() === $user->getId()) {
    $mngr->removeGalerie($gal);
    echo '{"result":true}';
} else {
    echo '{"result":false}';
}