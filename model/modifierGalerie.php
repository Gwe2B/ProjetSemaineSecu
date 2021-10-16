<?php

require_once ROOT."controllers".DS."Galerie.php";
require_once ROOT."controllers".DS."GalerieManager.php";

$errMess = null;
function clean(string $string) : string {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

$mngr = GalerieManager::getInstance($db);
$gal = $mngr->getById(intval($_GET['galId']));

if($gal != null && $gal->getUser_Id() === $user->getId()) {
    
	if(isset($_POST['nom']) && !empty($_POST['nom'])) {
		
		$nomGal = clean($_POST['nom']);
		$gal->setName($nomGal);
        $mngr->updateGalerie($gal);
        
    }
	
   // echo '{"result":true}';
} else {
  //  echo '{"result":false}';
}

$pageContent = array(
    'errMsg' => $errMess,
    'form' => $_POST
);