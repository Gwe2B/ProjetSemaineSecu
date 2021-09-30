<?php

require_once ROOT."controllers".DS."UserManager.php";

$errMsg = null;
$success = false;

if(isset($_POST['oldMdp']) && isset($_POST['newMdp']) && isset($_POST['confirm']) && 
!(empty($_POST['oldMdp']) || empty($_POST['newMdp']) || empty($_POST['confirm']))) {
    $usrMng = UserManager::getInstance($db);
    if($usrMng->isPasswordCorrect($user, $_POST['oldMdp']) && $_POST['newMdp'] === $_POST['confirm']) {
        if(!$usrMng->changePassword($user, $_POST['newMdp'])) {
            $errMsg = ERRORS_MSG['INVALID_PASSWORD'];
        } else {
            $success = true;
        }
    } else {
        $errMsg = ERRORS_MSG['DIFFERENT_PASSWORD'];
    }
}

$pageContent = array(
    'errMsg' => $errMsg,
    'success' => $success
);