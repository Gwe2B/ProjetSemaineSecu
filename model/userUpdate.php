<?php

require_once ROOT."controllers".DS."UserManager.php";

if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mail']) &&
isset($_POST['tel']) && isset($_POST['adresse']) && isset($_POST['description'])) {
    $usrMngr = UserManager::getInstance($db);

    $user->setNom($_POST['nom']);
    $user->setPrenom($_POST['prenom']);
    $user->setMail($_POST['mail']);
    $user->setTel($_POST['tel']);
    $user->setAdresse($_POST['adresse']);
    $user->setDescription($_POST['description']);

    $usrMngr->updateUser($user);
    $_SESSION['user'] = serialize($user);

    echo "<script>parent.window.location.reload();</script>";
}

$pageContent = array(
    'formDatas' => array(
        'nom'         => $user->getNom(),
        'prenom'      => $user->getPrenom(),
        'mail'        => $user->getMail(),
        'tel'         => $user->getTel(),
        'adresse'     => $user->getAdresse(),
        'description' => $user->getDescription()
    )
);