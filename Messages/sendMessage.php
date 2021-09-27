<?php
header('Content-type: application/json');

require_once "../controllers/User.php";
require_once "../controllers/UserManager.php";
require_once "../controllers/ChatMessage.php";
require_once "../controllers/Chat.php";

if(isset($_GET['fromUser']) && isset($_GET['toUser']) && isset($_GET['message'])
&& !empty($_GET['fromUser']) && !empty($_GET['toUser']) && !empty($_GET['message'])) {
    $db = null;
    try {
        // Connection à PDO
        $db = new PDO(
            "mysql:dbname=".$_SERVER['DB'].";host=".$_SERVER['DB_HOST'].";charset=UTF8",
            $_SERVER['DB_USER'],
            (isset($_SERVER['DB_PASS'])) ? $_SERVER['DB_PASS'] : null
        );

        // Set PDO variables
        //TODO: Prod, change PDO::ERRMODE_EXCEPTION by PDO::ERRMODE_SILENT
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    // Impossible de se connecter à la BDD
    catch (PDOException $e) {
        echo "Database connection error!!<br>";
        echo $e->getMessage();
    }

    $fromUser = intval($_GET['fromUser']);
    $toUser = intval($_GET['toUser']);
    $chat = new Chat($fromUser, $toUser, $db);

    $chat->sendMessage(new ChatMessage(array(
        'expediteur' => UserManager::getInstance($db)->getById($fromUser),
        'destinataire' => UserManager::getInstance($db)->getById($toUser),
        'content' => $_GET['message']
    )));

}