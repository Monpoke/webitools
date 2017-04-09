<?php
/* SCRIPT DU DISPATCHEUR */
// MODELE


// temps d'execution
define('DEBUT_SCRIPT', microtime(true));

// on demande la config
session_start();
$config_requise = true;
require_once('./inc/coeur.php');

$connect = @mysql_connect(SQL_HOST, SQL_USERNAME, SQL_PASSWORD) or require_once('templates/bd_inaccess.php');
$connect = @mysql_select_db(SQL_BDD, $connect) or require_once('templates/bd_inaccess.php');


// on verifie l'url
if(!isset($_GET['url'])){
	
	// on attribue une url par defaut
	$parametres = array('index');
}
else {
	
	// On commence a exploder l'url
	$parametres = @explode('/', $_GET['url']);
	
}

$dossier = $parametres[0];


@require_once('./inc/fonctions.php') or exit("Fonctions.php manquant");

if(is_file('./fichiers/spec_admin/maintenances/globale.php'))
	require_once('./fichiers/spec_admin/maintenances/globale.php');



if(@file_exists(PAGES. $dossier))
	$erreur = false;
	
else
	$erreur = true;
	
	
// VERIFICATION D'UNE ACTION
if(!empty($parametres[1]) && $erreur == false && preg_match('#\.xml$#', $parametres[1])){
	$fichier = $parametres[1];
}
elseif(!empty($parametres[1]) && $erreur == false){
	$fichier = $parametres[1] .'.php';
}
else {
	$fichier = "index.php";
}
	

	
/* EN CAS DE MAINTENANCE */
$verif_api = getParam(0);

if(isset(MAINTENANCE) && MAINTENANCE == true && $verif_api != API_KEY && getParam(-1) != "maintenance" && $_SERVER['REMOTE_ADDR'] != gethostbyname('monpoke.dyndns.org')){
	Redirect('/maintenance/globale');
}
	
	
// ON PASSE A L'INTERNATIONNALISATION DU SITE (LANGAGE)
if(isset(TRADUCTIONS) && TRADUCTIONS)
	require_once("./inc/traductions.php");
	
	
// verif anti-bot connecte
if(is_bot()){
	Redirect("/", 5);
echo <<<HTML
<html>
	<head>
		<title>Hum</title>
	</head>
	
	<body>
		Hum... Tu ne devrais pas &ecirc;tre connect&eacute; ...<br /><br /> Caput !

	</body>
</html>
HTML;

session_unset();
session_destroy();


	exit;
	


}
	// header ('Content-Type: text/html; charset=UTF-8');
	
	
// LES VERIFICATIONS FAITES DE L'URL, ON PASSE A L'INCLUSION
// On commence une mise en cache
ob_start();


if(!preg_match("#{$_SERVER['SERVER_NAME']}#", HTTP_DIR))
	Redirect(HTTP_DIR.$_SERVER['REQUEST_URI']);


// Si il n'y a pas d'erreur
if($erreur==false){

	
	/* on verifie si un fichier special existe */
	if(@is_file(PAGES."/".$dossier."/special_".$dossier.".php"))
		require_once(PAGES."/".$dossier."/special_".$dossier.".php");
	
	
	
	if($dossier=="crons")
		Redirect("/Pages/crons");
		
	elseif($dossier=="genPHP" && preg_match('#\.xml$#', $fichier))
		require(PAGES. $dossier ."/". $fichier);
	
	elseif(preg_match("#special_$dossier#", $fichier)) // page 404 en cas de demande incorecte
		require_once(PAGES. 'erreurs/404.php');
	
	elseif(@is_file(PAGES. $dossier .'/'. $fichier))
		require(PAGES. $dossier ."/". $fichier);
		
	elseif(!@is_file(PAGES. $dossier .'/'. $fichier) and @is_file(PAGES. $dossier .'/index.php'))
		require(PAGES. $dossier ."/index.php");
	
	
	
	elseif(@file_exists(PAGES. 'erreurs/404.php')) {
		require_once(PAGES. 'erreurs/404.php');
	}
	else {
		@header("Status: 404 Not Found");
		@die('Erreur 404 !');
	}
		
	
}
elseif(@file_exists(PAGES. 'erreurs/404.php')) {
	require_once(PAGES. 'erreurs/404.php');
}
else {
	@header("Status: 404 Not Found");
	@die('Erreur 404 !');

}



	
$content = @ob_get_clean();



echo $content;
	
// fermeture du sql
@mysql_close();
	
	