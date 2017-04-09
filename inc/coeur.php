<?php


/* COEUR DE LA CONFIGURATION DE PLUME d'OR */
if (!isset($config_requise) or $config_requise != true)
    die("Impossible d'afficher le fichier...");

ini_set('display_errors', -1);
date_default_timezone_set('Europe/Paris');

error_reporting(E_ALL);


// Informations de connexion a la BDD
if (!defined('SQL_HOST'))
    define('SQL_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
if (!defined('SQL_PORT'))
    define('SQL_PORT', getenv('OPENSHIFT_MYSQL_DB_PORT'));
	
if (!defined('SQL_USERNAME'))
    define('SQL_USERNAME', getenv('OPENSHIFT_MYSQL_DB_USERNAME'));

if (!defined('SQL_PASSWORD'))
    define('SQL_PASSWORD', getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));

if (!defined('SQL_BDD'))
    define('SQL_BDD', getenv('OPENSHIFT_GEAR_NAME'));

// On d�finit une cl� de s�curit�
if (!defined('SECURITY_KEY'))
    define('SECURITY_KEY', 'anonymised');

if (!defined('REV_KEY'))
    define('REV_KEY', 'anonymised');


if (!defined('PUB_PLUME'))
    define('PUB_PLUME', false);

if(!defined('AIDE_LIEN')){
	define('AIDE_LIEN', 'anonymised');
}

$listesPublicites = array(
	'Powered by <a href="/" class="go">WebiTools</a>',
	'<strong> Découvrez <a href="http://www.plumedor.fr/" class="go">Plume d\'Or</a></strong>',

);
	
	
if (!defined('PUBLICITE'))
   define('PUBLICITE', $listesPublicites[array_rand($listesPublicites)]);
    


// api CA
if (!defined('API_KEY'))
    define('API_KEY', 'anonymised');


// On definit les levels de l'user
if (!defined('VISITEUR'))
    define('VISITEUR', '0');

if (!defined('MEMBRE'))
    define('MEMBRE', 1);

if (!defined('MODERATEUR'))
    define('MODERATEUR', '2');

if (!defined('ADMINISTRATEUR'))
    define('ADMINISTRATEUR', '3');

if (!defined('BOT'))
    define('BOT', '5');

	


// On definit les emails jetables
$email_jetables = array(
    'yopmail.com'
);

$pseudos_reserves = array(
    'Monpoke'
);

$developpers = array(2, 3);

// Informations globales d'h�bergement
if (!defined('dev'))
    define('dev',true);

if (!defined('ROOT'))
    define('ROOT', '/');

if (!defined('SROOT'))
    define('SROOT', $_SERVER['DOCUMENT_ROOT'] . "/");

if (!defined('HTTP_DIR'))
    define('HTTP_DIR', 'http://webis.plumedor.fr/');

if (!defined('HTTP_PUBLIC'))
    define('HTTP_PUBLIC', 'http://webi.fr.nf/');


if (!defined('PAGES'))
    define('PAGES', './Pages/');


// On autorise l'inclusion de la page
$authorization_to_inclure = true;

error_reporting(E_ALL);


if (!isset($_SESSION['auth_membre']))
    $_SESSION['auth_membre'] = false;
