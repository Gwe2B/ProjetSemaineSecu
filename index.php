<?php
session_start(); //*Mise en place de $_SESSION pour les messages flash et autres

/* ------------------------ Définition des constantes ----------------------- */
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(__FILE__).DS);

// Get errors messages
define(
    "ERRORS_MSG",
    json_decode(file_get_contents(ROOT."errorMessages.json"),
    true
));
/* -------------------------------------------------------------------------- */

/* ----------------------- Implementation des classes ----------------------- */
require_once ROOT."vendor".DS."autoload.php";
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
/* -------------------------------------------------------------------------- */

/* ------------------------- Initialisation de Twig ------------------------- */
$loader = new \Twig\Loader\FilesystemLoader("view");
$twig = new \Twig\Environment($loader, array(
    'cache' => false, // TODO: change `false` to `'cache'` for production
    'autoescape' => "html",
    'debug' => true // TODO: change `true` to `false` for production
));
/* -------------------------------------------------------------------------- */

/* -------------------------------------------------------------------------- */
/*                                   Routeur                                  */
/* -------------------------------------------------------------------------- */
// TODO: make a verification on the sessioned user

$pageContent = array();
$template = null;
if(isset($_SESSION['user'])&&!empty($_SESSION['user'])) {
    require_once ROOT."controllers".DS."User.php";
    $user = unserialize($_SESSION['user']);
    
    if(isset($_GET["disconnect"])) {
        session_destroy();
        header("Location: index.php");
        
    } else if(isset($_GET["utilisateur"])) { 
        require_once ROOT."model".DS."utilisateur.php";
        $template = $twig->load("utilisateur.twig");
        
    } else if(isset($_GET["galerie"])) { 
        require_once ROOT."model".DS."galerie.php";
        $template = $twig->load("galerie.twig");
		
	} else if(isset($_GET["ajoutImage"])) { 
        require_once ROOT."model".DS."ajoutImage.php";
        $template = $twig->load("ajoutImage.twig");
        
    } else if(isset($_GET["formulaire"])) { 
        require_once ROOT."model".DS."formulaire.php";
        $template = $twig->load("formulaire.twig");
        
    } else {
        require_once ROOT."model".DS."acceuil.php";
        $template = $twig->load('acceuil.twig');
    }

    $pageContent['page'] = array_keys($_GET)[0];
} else {
    if(isset($_GET["askRecup"])) {
        require_once ROOT."model".DS."askRecup.php";
        $template = $twig->load("askRecup.twig");
    } else if(isset($_GET["recupMdp"]) && !empty($_GET["recupMdp"])) {
        require_once ROOT."model".DS."recupMdp.php";
        $template = $twig->load("recupMdp.twig");
    } else {
        require_once ROOT."model".DS."login.php";
        $template = $twig->load("login.twig");
    }
}

if($template != null) {
    echo $template->render($pageContent);
}
/* -------------------------------------------------------------------------- */