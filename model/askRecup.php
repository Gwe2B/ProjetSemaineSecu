<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once ROOT."controllers".DS."User.php";
require_once ROOT."controllers".DS."UserManager.php";

function genererChaineAleatoire($longueur = 10) {
    return substr(
        str_shuffle(
            str_repeat(
                $x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                ceil($longueur/strlen($x))
            )
        ),
        1,
        $longueur
    );
}

$errMsg = null;

if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $usrMngr = UserManager::getInstance($db);
    $usr = $usrMngr->getByMail($_POST['email']);
    if($usr != null) {
        $token = md5(date('Y-m-d')).genererChaineAleatoire(100);
        $usrMngr->addLink($usr, $token);

        $mailer = new PHPMailer();
        $mailer->isSMTP(true); // telling the class to use SMTP
        $mailer->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            )
        );

        //Enable SMTP debugging
        //SMTP::DEBUG_OFF = off (for production use)
        //SMTP::DEBUG_CLIENT = client messages
        //SMTP::DEBUG_SERVER = client and server messages
        $mailer->SMTPDebug = SMTP::DEBUG_SERVER;

        $mailer->Host = $_SERVER['MAIL_HOST'];
        $mailer->Port = intval($_SERVER['MAIL_PORT']);
        $mailer->SMTPSecure = $_SERVER['MAIL_PROT'];

        $mailer->SMTPAuth = true;
        $mailer->Username = $_SERVER['MAIL_USER'];
        $mailer->Password = $_SERVER['MAIL_PASS'];

        $mailer->CharSet = "utf-8";
        $mailer->isHTML(true);

        $mailer->setFrom("instaxmaster@gmail.com", "The Master 1 Instagram");
        $mailer->addAddress(htmlspecialchars($_POST['email']), htmlspecialchars($_POST['email']));

        $mailer->Subject = "Création d'un nouveau mot de passe.";
        $mailer->Body = '<html>
    <head>
        <style>
            * { font-family: sans-serif; }
            .button {
                border-radius: 1em;
                width: 60%;
                background-color: lightblue;
                color: white;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <em>Ceci est un message automatique, merci de ne pas répondre.</em>
        <p>
            La génération d\'un lien pour un nouveau mot de passe à été demander.<br>
            Si la demande ne vient pas de vous, merci d\'ignorer ce message et d\'avertir
            un administrateur au plus vite.
        </p>
        <p>
            <strong>ATTENTION: Le lien à une validité de 24 heures!</strong>
        </p>
        <a href="'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?recupMdp='.$token.'" class="button">Lien de création du mot de passe.</a>
    </body>
</html>';

        $mailer->AltBody = "Ceci est un message automatique, merci de ne pas répondre.\n\n
La génération d'un lien pour un nouveau mot de passe à été demander.\n
Si la demande ne vient pas de vous, merci d'ignorer ce message et d'avertir
un administrateur au plus vite.\n\n
ATTENTION: Le lien à une validité de 24 heures!\n
".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?recupMdp='.$token;

        if(!$mailer->send()) {
            echo "Mailer Error: ".$mailer->ErrorInfo;
        }
    }

    $errMsg = ERRORS_MSG['RECUP_MESSAGE'];
}

$pageContent = array(
    'errMsg' => $errMsg
);