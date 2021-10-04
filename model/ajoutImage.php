<?php

require_once ROOT."controllers".DS."ImageUploader.php";
require_once ROOT."controllers".DS."Image.php";
require_once ROOT."controllers".DS."ImageManager.php";

$errMess = null;

function clean(string $string) : string {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

if(isset($_GET['gallerie']) && !empty($_GET['gallerie'])) {
    $gallerie = intval($_GET['gallerie']);
} else {
    echo "<script>parent.hs.close();</script>";
}

if(isset($_POST['confident']) && !empty($_POST['confident']) && isset($_FILES['picture'])) {
    $confident = Image::getVisibilityFromString($_POST['confident']);
    $destFolder = strtolower($user->getId()."_".clean($user->getNom()).".".clean($user->getPrenom()));
    $upload = ImageUploader::uploadImage($destFolder, $_FILES['picture']);

    if($upload != null) {
        $imgMng = ImageManager::getInstance($db);
        $img = new Image(array(
            'path' => $upload,
            'user_id' => $user->getId(),
            'visibility' => $confident,
            'gallerie_id' => $gallerie
        ));

        $imgMng->addImage($img);

        echo "<script>parent.window.location.reload();</script>";
    } else {
        $errMess = ERRORS_MSG['UNABLE_UPLOAD'];
    }
}

$pageContent = array(
    'errMsg' => $errMess,
    'gallerie' => $gallerie,
    'form' => $_POST
);