<?php
session_start(); //*Mise en place de $_SESSION pour les messages flash et autres

/* ------------------------ Définition des constantes ----------------------- */
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(__FILE__).DS);
/* -------------------------------------------------------------------------- */

/* ----------------------- Implementation des classes ----------------------- */
require_once ROOT."vendor/autoload.php";
//require_once ROOT."class/SGBD.php";

/*$db = new SGBD(
    "mysql:dbname=".$_SERVER['DB'].";host=".$_SERVER['DB_HOST'].";charset=UTF8",
    $_SERVER['DB_USER'],
    $_SERVER['DB_PASS'], true
);*/
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