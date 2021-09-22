<?php

require_once ROOT."controllers".DS."User.php";
require_once ROOT."controllers".DS."UserManager.php";

$usrMngr = UserManager::getInstance($db);
$usr = $usrMngr->getByLink($_GET['recupMdp']);

$errMsg = null;

if($usr == null) {
    $_SESSION['errMsg'] = ERRORS_MSG['INVALID_LINK'];
    header("Location: index.php");
} else if(isset($_POST['mdp']) && isset($_POST['confirmMdp']) &&
!(empty($_POST['mdp']) || empty($_POST['confirmMdp']))) {
    if($_POST['mdp'] === $_POST['confirmMdp']) {
        if($usrMngr->changePassword($usr, $_POST['mdp'])) {
            $_SESSION['errMsg'] = ERRORS_MSG['PASSWORD_CHANGED'];
            header("Location: index.php");
        } else {
            $errMsg = ERRORS_MSG['INVALID_PASSWORD'];
        }
    } else {
        $errMsg = ERRORS_MSG['DIFFERENT_PASSWORD'];
    }
}

$pageContent = array(
    'token' => $_GET['recupMdp'],
    'errMsg' => $errMsg
);