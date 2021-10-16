<?php

require_once ROOT."controllers".DS."Image.php";
require_once ROOT."controllers".DS."ImageManager.php";

$pageContent = array();

if(isset($_GET['imageId']) && !empty($_GET['imageId'])) {
    $mngr = ImageManager::getInstance($db);
    $image = $mngr->getById(intval($_GET['imageId']));

    if($image->getUser_Id() === $user->getId()) {
        if(isset($_POST['confident']) && !empty($_POST['confident'])) {
            echo "post";
            $confident = Image::getVisibilityFromString($_POST['confident']);
            $image->setVisibility($confident);
            $mngr->updateImage($image);

            echo '<script>parent.window.location.reload();</script>';
        }

        $pageContent['image'] = $image;
    } else {
        echo '<script>parent.window.location.reload();</script>';
    }
} else {
    echo '<script>parent.window.location.reload();</script>';
}