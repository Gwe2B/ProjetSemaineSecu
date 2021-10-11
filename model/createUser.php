<?php

require_once ROOT."controllers".DS."User.php";
require_once ROOT."controllers".DS."UserManager.php";

$errMsg = null;
$mngr = UserManager::getInstance($db);

if(isset($_POST['email']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mdp']) && isset($_POST['confirmMdp']) &&
!(empty($_POST['email']) || empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['mdp']) || empty($_POST['confirmMdp']))) {
    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        if($_POST['mdp'] === $_POST['confirmMdp']) {
            if(preg_match(UserManager::REGEX_PASSWORD, $_POST['mdp'])) {
                // Creation of the user
                $usr = new User(array());
                $usr->setMail($_POST['email']);
                $usr->setNom(htmlspecialchars($_POST['nom']));
                $usr->setPrenom(htmlspecialchars($_POST['prenom']));
                $mngr->createUser($usr);

                // Création of the password
                $mngr->changePassword($usr, htmlspecialchars($_POST['mdp']));

                $_SESSION['errMsg'] = ERRORS_MSG['USER_CREATED'];
                header("Location: index.php");
            } else {
                $errMsg = ERRORS_MSG['INVALID_PASSWORD'];
            }
        } else {
            $errMsg = ERRORS_MSG['DIFFERENT_PASSWORD'];
        }

    } else {
        $errMsg = ERRORS_MSG['INVALID_EMAIL'];
    }
}

$pageContent = array(
    'errMsg' => $errMsg
);