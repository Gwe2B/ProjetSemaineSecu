<?php
require_once ROOT."controllers".DS."User.php";
require_once ROOT."controllers".DS."UserManager.php";

if(isset($_SESSION['errMsg']) && !empty($_SESSION['errMsg'])) {
    $errMsg = $_SESSION['errMsg'];
    unset($_SESSION['errMsg']);
} else {
    $errMsg = null;
}

if(isset($_POST['email'])&&isset($_POST['pwd'])&&
!(empty($_POST['email'])||empty($_POST['pwd']))) {
    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email = $_POST['email'];
        $pwd = htmlspecialchars($_POST['pwd']);

        $usrMngr = UserManager::getInstance($db);
        $usr = $usrMngr->connectUser($email, $pwd);
        if($usr != null) {
            $_SESSION['user'] = serialize($usr);
            header("Location: index.php");
        } else {
            $errMsg = ERRORS_MSG['BAD_CREDENTIALS'];
        }
    } else {
        $errMsg = ERRORS_MSG['INVALID_EMAIL'];
    }
}

$pageContent = array(
    'email' => isset($_POST['email']) ? $_POST['email'] : "",
    'errMsg' => $errMsg
);