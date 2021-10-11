<?php

require_once ROOT."controllers".DS."Galerie.php";
require_once ROOT."controllers".DS."GalerieManager.php";

$errMess = null;
function clean(string $string) : string {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

    if(isset($_POST['nom']) && !empty($_POST['nom'])) {
		
		$nomGal = clean($_POST['nom']);
		
		$galMng = GalerieManager::getInstance($db);
        $gal = new Galerie(array(
                    'name' => $nomGal,
                    'user_id' => $user->getId()
                ));

        $galMng->addGalerie($gal);
		echo "<script>parent.window.location.reload();</script>";
        
    }


$pageContent = array(
    'errMsg' => $errMess,
    'form' => $_POST
);