<?php

require_once ROOT."controllers".DS."Galerie.php";
require_once ROOT."controllers".DS."GalerieManager.php";

function clean(string $string) : string {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

$pageContent = array();

if(isset($_GET['galerieId']) && !empty($_GET['galerieId'])) {
    $mngr = GalerieManager::getInstance($db);
    $gal = $mngr->getById(intval($_GET['galerieId']));

    if($gal != null && $gal->getUser_Id() === $user->getId()) {
    
	    if(isset($_POST['nom']) && !empty($_POST['nom'])) {
		   echo "post";
		   $nomGal = clean($_POST['nom']);
		   $gal->setName($nomGal);
           $mngr->updateGalerie($gal);
		
	        echo '<script>parent.window.location.reload();</script>';
        } 	
		
        $pageContent['galerie'] = $gal;		
		
    } else {
        echo '<script>parent.window.location.reload();</script>';
    }
} else {
    echo '<script>parent.window.location.reload();</script>';
}