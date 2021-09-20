<?php
session_start(); //*Mise en place de $_SESSION pour les messages flash et autres

/* ------------------------ Définition des constantes ----------------------- */
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(__FILE__).DS);
/* -------------------------------------------------------------------------- */

/* ----------------------- Implementation des classes ----------------------- */
require_once ROOT."vendor/autoload.php";
/* -------------------------------------------------------------------------- */

/* -------------------------- Initialisation de PDO ------------------------- */
$db = null;
try {
    // Connection à PDO
    $db = new PDO(
        "mysql:dbname=".$_SERVER['DB'].";host=".$_SERVER['DB_HOST'].";charset=UTF8",
        $_SERVER['DB_USER'],
        (isset($_SERVER['DB_PASS'])) ? $_SERVER['DB_PASS'] : null
    );

    //TODO: Prod, change PDO::ERRMODE_EXCEPTION by PDO::ERRMODE_SILENT
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
// Impossible de se connecter à la BDD
catch (PDOException $e) {
    echo "Database connection error!!<br>";
    echo $e->getMessage();
}
/* -------------------------------------------------------------------------- */

/* ------------------------- Initialisation de Twig ------------------------- */
$loader = new \Twig\Loader\FilesystemLoader("view");
$twig = new \Twig\Environment($loader, array(
    'cache' => false,
    'autoescape' => "html",
    'debug' => true
));
/* -------------------------------------------------------------------------- */

/* -------------------------------------------------------------------------- */
/*                                   Routeur                                  */
/* -------------------------------------------------------------------------- */
if(isset($_GET["login"])) {
    require_once ROOT."model/login.php";
    $template = $twig->load("login.twig"); //TODO: Remplacer pageName
}
else {
    require_once ROOT."model/acceuil.php";
    $template = $twig->load('acceuil.twig');
}

echo $template->render($pageContent);
/* -------------------------------------------------------------------------- */