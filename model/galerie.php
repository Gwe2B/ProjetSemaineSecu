<?php
require_once ROOT."controllers".DS."Galerie.php";
require_once ROOT."controllers".DS."GalerieManager.php";

$gm = GalerieManager::getInstance($db);

$pageContent = array(
    'galeries'=> $gm->getByUserId($user->getId()),
	'galeriesVides'=> $gm->getEmptyGaleriesByUserId($user->getId()),
);

//var_dump($gm->getByUserId($user->getId()));

// $galeries = $gm->getByUserId($user->getId());
// foreach($galeries as $gal) {
	// var_dump($gal->getImages());
	// echo("*************");
// }

//$images = $gm->getImagesByGalerieId(13);

//$gal = $gm->getById(13);
//$gm->removeGalerie($gal);



