<?php

require_once ROOT."controllers".DS."Galerie.php";
require_once ROOT."controllers".DS."GalerieManager.php";
require_once ROOT."controllers".DS."ImageUploader.php";
require_once ROOT."controllers".DS."Image.php";
require_once ROOT."controllers".DS."ImageManager.php";

$errMess = null;
function clean(string $string) : string {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

if(isset($_GET['gallerie']) && !empty($_GET['gallerie'])) {
    $gallerie_id = intval($_GET['gallerie']);
    $gallerie = GalerieManager::getInstance($db)->getById($gallerie_id);

    if($user->getId() != $gallerie->getUser_Id()) {
        echo '<div class="ui negative message">
        <i class="close icon"></i>
        <div class="header">
          Désoler...
        </div>
        <p>
        Une erreur de lien est survenue. Veuillez rafraichir la page.</p>
        </div>';
        echo "<script>parent.hs.close();</script>";
    } else if(isset($_POST['confident']) && !empty($_POST['confident']) && isset($_FILES['picture'])) {
        $confident = Image::getVisibilityFromString($_POST['confident']);
        $destFolder = strtolower($user->getId()."_".clean($user->getNom()).".".clean($user->getPrenom()));

        $nbUploads = count($_FILES['picture']['name']);
        for($i = 0; $i < $nbUploads; $i++) {
            $file = array(
                'name' => $_FILES['picture']['name'][$i],
                'tmp_name' => $_FILES['picture']['tmp_name'][$i],
                'type' => $_FILES['picture']['type'][$i],
                'error' => $_FILES['picture']['error'][$i],
                'size' => $_FILES['picture']['size'][$i],
            );

            $upload = ImageUploader::uploadImage($destFolder, $file);

            if($upload != null) {
                $imgMng = ImageManager::getInstance($db);
                $img = new Image(array(
                    'path' => $upload,
                    'user_id' => $user->getId(),
                    'visibility' => $confident,
                    'gallerie_id' => $gallerie_id
                ));

                $imgMng->addImage($img);

                echo "<script>parent.window.location.reload();</script>";
            } else {
                $errMess = ERRORS_MSG['UNABLE_UPLOAD'];
            }
        }
    }

    unset($gallerie);
} else {
    echo "<script>parent.hs.close();</script>";
}

$pageContent = array(
    'errMsg' => $errMess,
    'gallerie' => $gallerie_id,
    'form' => $_POST
);