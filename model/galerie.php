<?php
require_once ROOT."controllers".DS."Galerie.php";
require_once ROOT."controllers".DS."GalerieManager.php";

$gm = GalerieManager::getInstance($db);

$pageContent = array(
    'galeries'=> $gm->getByUserId($user->getId()),
);

//var_dump($gm->getByUserId($user->getId()));