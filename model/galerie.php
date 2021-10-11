<?php
require_once ROOT."controllers".DS."Galerie.php";
require_once ROOT."controllers".DS."GalerieManager.php";

$gm = GalerieManager::getInstance($db);

$pageContent = array(
    'galeries'=> $gm->getByUserId($user->getId()),
	'galeriesVides'=> $gm->getEmptyGaleriesByUserId($user->getId()),
);

//var_dump($gm->getEmptyGaleriesByUserId($user->getId()));