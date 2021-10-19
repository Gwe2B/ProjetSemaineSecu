<?php
require_once ROOT."controllers".DS."Galerie.php";
require_once ROOT."controllers".DS."GalerieManager.php";
require_once ROOT."controllers".DS."User.php";
require_once ROOT."controllers".DS."UserManager.php";

$gm = GalerieManager::getInstance($db);
$um = UserManager::getInstance($db);

$pageContent = array(
    'galeries'=> $gm->getByOtherUserId(intval($_GET['friendId']),$user->getId()),
	'friend'=> $um->getById(intval($_GET['friendId'])),
);

//var_dump(intval($_GET['friendId']));

//var_dump($gm->getByOtherUserId($user->getId()));

// $galeries = $gm->getByUserId($user->getId());
// foreach($galeries as $gal) {
	// var_dump($gal->getImages());
	// echo("*************");
// }

//$images = $gm->getImagesByGalerieId(13);

//$gal = $gm->getById(13);
//$gm->removeGalerie($gal);



