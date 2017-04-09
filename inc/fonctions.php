<?php
if(!isset($config_requise) or $config_requise != true)
	exit("Impossible d'afficher le fichier...");

// fonction pour convertir les parametres passes en url
function getParam($place=1){
	global $parametres;
	
	$i= $place + 1;
	if(isset($parametres[ $i ]))
		return $parametres[ $i ];
	else
		return "";
}

// autorisation d'inclure une page
function check(){
	global $authorization_to_inclure;
	
	if(!isset($authorization_to_inclure) OR $authorization_to_inclure == false)
		Redirect(HTTP_DIR .'/erreurs/404');
}

// fonction pour verifier si c'est un membre (modules)
function membre($id_site){
	if(isset($_SESSION['auth_'.$id_site]))
		return true;
	else
		return false;


}

// cryptage reversible
function chiffre($cle){
	$code = base64_encode($cle);

	$code = preg_replace('#=#Usi', "", $code);
	return $code;
}



function dechiffre($pseudo){
	return base64_decode($pseudo);
}
  
  
  
  
function converseTime($date)
{
	if(preg_match("#-#", $date))
		$exp=explode('-', $date);
	else
		$exp=explode('/', $date);
	
	if(count($exp) != 3)
		return false;
	
	list($day, $month, $year) = $exp;
	
	if(!checkdate($month, $day, $year))
		return false;
		
	
	
	$timestamp = mktime(0, 0, 0, $month, $day, $year);
	return $timestamp;
}

function isUrl($url){
	$url = strtolower($url);
	if(!preg_match('#(((https?)://(w{3}\.)?)(?<!www)(\w+-?)*\.([a-z]{2,4}))#', $url)){
		return false;
	}
	else
		return true;
}

// fonction historique
// fonction qui convertit un timestamp en date lisible pour un humain
function converseDate($time, $programme = 1){
	
		$time = htmlspecialchars($time);
		$jour1 = date('N', $time);
		$numero = date('j', $time);
		$mois1 = date('n', $time);
		$annee = date('Y', $time);
		$heure = date('G', $time);
		$minute = date('i', $time);
		
		switch($jour1){
			
			case 1;
				$jour = _("Lundi ");
			break;
			
			case 2;
				$jour = _("Mardi ");
			break;
			
			case 3;
				$jour = _("Mercredi ");
			break;
			
			case 4;
				$jour = _("Jeudi ");
			break;
			
			case 5;
				$jour = _("Vendredi ");
			break;
			
			case 6;
				$jour = _("Samedi ");
			break;
			
			case 7;
				$jour = _("Dimanche ");
			break;
			
			
		}

		switch($mois1){
				
				case 1;
					$mois = _(" janvier ");
				break;
				
				case 2;
					$mois = _(" f&eacute;vrier ");
				break;
				
				case 3;
					$mois = _(" mars ");
				break;
				
				case 4;
					$mois = _(" avril ");
				break;
				
				case 5;
					$mois = _(" mai ");
				break;
				
				case 6;
					$mois = _(" juin ");
				break;
				
				case 7;
					$mois = _(" juillet ");
				break;
				
				case 8;
					$mois = _(" ao&ucirc;t ");
				break;
				
				case 9;
					$mois = _(" septembre ");
				break;
				
				case 10;
					$mois = _(" octobre ");
				break;
				
				case 11;
					$mois = _(" novembre ");
				break;
				
				case 12;
					$mois = _(" d&eacute;cembre ");
				break;
				
			}	
		
		
		if($programme == 1)
			$date = $jour.$numero.$mois.$annee . _(" &agrave; ") .$heure.":".$minute;
		
		elseif($programme==2)
			$date = $jour.$numero.$mois.$annee;
		
		
		elseif($programme==3)
			$date = $heure.":".$minute;
			
		elseif($programme==4)
			$date = $jour;
			
		elseif($programme==5)
			$date = $mois;
		
		elseif($programme ==6)
			$date = $jour.$mois;
		
		elseif($programme==7)
			$date = $numero.'/'.$mois1.'/'.$annee.' '.$heure.':'.$minute;
			
		elseif($programme==8)
			$date = $jour.$numero.$mois;
			
		elseif($programme == 9)
			$date = $numero."/".$mois1."/".$annee . _(" &agrave; ") .$heure.":".$minute;
		
		elseif($programme==10)
			$date = $numero."/".$mois1."/".$annee;
			
		elseif($programme==11)
			$date = $numero."/".$mois1. _(" &agrave; ") .$heure.":".$minute;
			
		
		else
			$date = "Param&egrave;tres incorrects !";
			

	
	
	return $date;
}	
 
// fonction de redirection
function Redirect($adresse, $temps = NULL){
	if($temps==null){
		// Debog($adresse);
		header("Location: $adresse");
		exit(_("<a href=\"$adresse\" title=\"Cliquez ici\">Si la redirection ne se fait pas, cliquez ici.</a>"));
	}
	else {
		header("Refresh:$temps; url=$adresse");
	}
}


// Fonctions pour verifier le niveau de l'utilisateur
function is_visiteur(){
	
	if(!isset($_SESSION['auth_membre']) || $_SESSION['auth_membre']==false)
		return true;
	else
		return false;	
}
	
function is_membre($strict=false){
	
	if($strict==false){
		
		if($_SESSION['auth_membre']==true && $_SESSION['donnees_membre']['rang'] >= MEMBRE && $_SESSION['donnees_membre']['rang'] != BOT)
			return true;
		else
			return false;	
			
	}
	else {
		if($_SESSION['auth_membre']==true && $_SESSION['donnees_membre']['rang'] == MEMBRE)
			return true;
		else
			return false;	
			
	
	}
}

function is_modo($strict=false){
	
	if($strict==false){
		
		if($_SESSION['auth_membre']==true && $_SESSION['donnees_membre']['rang'] >= MODERATEUR && $_SESSION['donnees_membre']['rang'] != BOT)
			return true;
		else
			return false;	
			
	}
	else {
		if($_SESSION['auth_membre']==true && $_SESSION['donnees_membre']['rang'] == MODERATEUR)
			return true;
		else
			return false;	
			
	
	}
}

function is_admin($strict=false){
	
	if($strict==false){
		
		if($_SESSION['auth_membre']==true && $_SESSION['donnees_membre']['rang'] >= ADMINISTRATEUR && $_SESSION['donnees_membre']['rang'] != BOT)
			return true;
		else
			return false;	
			
	}
	else {
		if($_SESSION['auth_membre']==true && $_SESSION['donnees_membre']['rang'] == ADMINISTRATEUR)
			return true;
		else
			return false;	
			
	
	}
}

function is_premium($id_joueur="", $verif_illimite=false){
	
	if(is_membre() and empty($id_joueur) and $verif_illimite==false){
		if(($_SESSION['donnees_membre']['premium'] == "1" and time()<$_SESSION['donnees_membre']['fin_premium'] ) OR $_SESSION['donnees_membre']['rang'] == ADMINISTRATEUR or ($_SESSION['donnees_membre']['fin_premium'] == "-1" && $_SESSION['donnees_membre']['premium'] == "1"))
			return true;
		else
			return false;	
		
	
	}
	elseif(is_membre() and empty($id_joueur) && $verif_illimite){
		if($_SESSION['donnees_membre']['rang'] == ADMINISTRATEUR or ($_SESSION['donnees_membre']['fin_premium'] == "-1" && $_SESSION['donnees_membre']['premium'] == "1"))
			return true;
		else
			return false;	
		
	
	}
	
	elseif(!empty($id_joueur) && !$verif_illimite){
		
		$id_joueur = intval($id_joueur);
		
		$joueur = Joueur($id_joueur);
		
		if(($joueur['premium'] == "1" and time() < $joueur['fin_premium']) or $joueur['rang']==ADMINISTRATEUR or ($joueur['premium'] == "1" && $joueur['fin_premium']=="-1"))
			return true;
		else
			return false;
			
		
		
	}
	
	elseif(!empty($id_joueur) && $verif_illimite){
		
		$id_joueur = intval($id_joueur);
		
		$joueur = Joueur($id_joueur);
		
		if($joueur['rang'] == ADMINISTRATEUR or ($joueur['fin_premium'] == "-1" && $joueur['premium'] == "1"))
			return true;
		else
			return false;	
			
	
	}
	else 
		return false;
}




function is_mascotte($id=0){
	global $config;
	
	if($id==1)
		return true;
	elseif($_SESSION['auth_membre']==true && $_SESSION['donnees_membre']['id'] == "1")
		return true;
	else
		return false;	
}

function is_bot(){
	global $config;
	
	if(isset($_SESSION['auth_membre']) && $_SESSION['auth_membre']==true && $_SESSION['donnees_membre']['rang'] == BOT)
		return true;
	else
		return false;	
}



function is_developper(){
	global $developpers;
	
	if(!is_membre() or !in_array(get('id'), $developpers))
		return false;
		
	else
		return true;
	
}



function post_request($url, $data, $referer='') {
 
    // Convert the data array into URL Parameters like a=b&foo=bar etc.
    $data = http_build_query($data);
 
    // parse the given URL
    $url = parse_url($url);
 
    if ($url['scheme'] != 'http') { 
        die('Error: Only HTTP request are supported !');
    }
 
    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];
 
    // open a socket connection on port 80 - timeout: 30 sec
    $fp = fsockopen($host, 80, $errno, $errstr, 10);
 
    if ($fp){
 
        // send the request headers:
        fputs($fp, "POST $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");
 
        if ($referer != '')
            fputs($fp, "Referer: $referer\r\n");
 
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ". strlen($data) ."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $data);
 
        $result = ''; 
        while(!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 128);
        }
    }
    else { 
        return array(
            'status' => 'err', 
            'error' => "$errstr ($errno)"
        );
    }
 
    // close the socket connection:
    fclose($fp);
 
    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);
 
    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';
 
    // return as structured array:
    return array(
        'status' => 'ok',
        'header' => $header,
        'content' => $content
    );
}

function post_request2($url, $data){
	
	// Les données envoyées en POST sous forme d'url
	
	$fp = fsockopen("http://webidev.com", 80, $errno, $errstr, 30);

	if (!$fp) {
		return array('status' => 'error', 'error' => $errstr." ($errno)");
	} else {
	
		//On forme les données à envoyer (HTTP1.1)
		$out = "POST /$url HTTP/1.1\r\n";
		$out .= "Host: http://webidev.com\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Content-type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: ".strlen($data)."\r\n\r\n";
		
		$out .= $data."\r\n";
		
		//envoi des données
		fwrite($fp, $out);

		//récupération de la page renvoyée par le serveur Web
		//(inutile s'il s'agit uniquement de poster des données)
		$resultat = "";
		while (!feof($fp)) {
			$resultat.= fgets($fp, 128);
		}
		
		fclose($fp);
		
		return array('status' => 'ok', 'content' => $resultat);
	}


}


$NBRE_REQUETES = 0;
// Fonction de recuperation pour les requetes sql
function Query($sql="", $debog=false){
	global $NBRE_REQUETES;
	$NBRE_REQUETES++;
	
	if($debog)
		echo $sql."<br />";
		
	elseif($sql!="" && !$debog && !preg_match("#controlmedia#", $_SERVER['REQUEST_URI'])){
		if($retour=mysql_query($sql)){
			return $retour;
			
			
		}
		else {
			if(is_visiteur())
				$membre = "0";
			else
				$membre = $_SESSION['donnees_membre']['id'];
				
			$date = time();
			$ip = $_SERVER['REMOTE_ADDR'];
			if(isset($_SERVER['HTTP_REFERER']))
				$referer = $_SERVER['HTTP_REFERER'];
			else	
				$referer ="";
			
			$query = mysql_connect(SQL_HOST, SQL_USERNAME, SQL_PASSWORD);
			$query = mysql_select_db(SQL_BDD, $query);
			
			// mysql_query("INSERT INTO erreurs_generees(nom, texte, membre, ip, date, referer, autre) VALUES('Requêtes', 'Une erreur de requete est survenue', '$membre', '$ip', '$date', '$referer', '".$sql."')");
			WriteLog("Erreur de requête : ".mysql_error()." ". $sql);
			
			if(is_developper())
				exit(_("<b>Erreur de requ&ecirc;te !</b> :<br />"). mysql_error()."<br />".$sql);
			else
				exit(_("Une erreur interne est survenue !"));
			
		}
	}
	// elseif(!$debog)
		// die('Erreur de requ&ecirc;te !');



}

function genSecuriteID($car){
	if($car<=40)
		return substr(Cryptage(chaine_unique2(20).$_SERVER['REMOTE_ADDR']), 0, $car);
	else
		return "40 maxi !";
}


// fonction pour generer une chaine aleatoire
function chaine_unique2($car) {


	$string = "";
	$chaine = "abcdefghijklmnpqrstuvwxy1234567890DFGHJKLMPO";
	
	srand((double)microtime()*1000000);
	for($i=0; $i<$car; $i++) {
		$string .= $chaine[rand()%strlen($chaine)];
		
	}
	return $string;
}



// fonctions pur les logs
function WriteLog($texte){

	$jour_fichier = date('Y_m_d');
	$jour = date('j/m/Y');
	
	
	$heure = date('H:j:s');
	$ip = $_SERVER['REMOTE_ADDR'];
	
	if($log = fopen($_SERVER['DOCUMENT_ROOT']."/logs/".$jour_fichier.".log", 'a+')){
	
		$message = "$jour $heure $ip] $texte<br />";
		
		
		if(fputs($log, $message)){
			return true;
			fclose($log);
		}
		else
			return false;
	}
	else
		return false;
}


// fonction d'autoremplissage des donnees
function input_value($chaine_origine, $chaine_postee){


	
	global $_POST;
	
	if(isset($_POST[$chaine_postee]))
		return htmlspecialchars($_POST[$chaine_postee]);
		// Debog('1');
	
	else
		return $chaine_origine;
		// Debog('2');
}


// fonction d'autoremplissage des chckbox
function check_value($nom, $coche=false){

	global $_POST;
	
	if(isset($_POST[$nom]))
		return 'checked="checked" ';
	
	elseif($coche==true)
		return 'checked="checked" ';
	
	else
		return "";
}


// fonction d'autoremplissage des select
function select_value($chaine_postee, $valeur, $coche_defaut=false){


	global $_POST;
	
	
	
	if(!isset($_POST[$chaine_postee]) && $coche_defaut==true)
		return 'checked="checked" ';
	
	elseif(!isset($_POST[$chaine_postee]) && $coche_defaut==false)
		return "";
		
	elseif(isset($_POST[$chaine_postee]) && $_POST[$chaine_postee] == $valeur)
		return 'checked="checked" ';
			
	else
		return "";
}


// fonction d'autoremplissage des select
function selecte_value($chaine_postee, $valeur, $coche_defaut=false){


	global $_POST;
	
	
	
	if(!isset($_POST[$chaine_postee]) && $coche_defaut==true)
		return 'selected="selected" ';
	
	elseif(!isset($_POST[$chaine_postee]) && $coche_defaut==false)
		return "";
		
	elseif(isset($_POST[$chaine_postee]) && $_POST[$chaine_postee] == $valeur)
		return 'selected="selected" ';
			
	else
		return "";
}




// fonction pour securiser une chaine
function Secure($chaine){
	$chaine_securise = mysql_real_escape_string(htmlspecialchars($chaine));
	return $chaine_securise;
}

// Fonction de cryptage
function cryptage($chaine){
	$chaine = sha1(SECURITY_KEY.$chaine.SECURITY_KEY);
	return $chaine;

}

function genPluriel($nombre, $sg, $pl, $aucun=""){
	$pl = preg_replace("#\{nbre\}#Uis", $nombre, $pl);
	
	if($nombre==0)
		return $aucun;
		
	elseif($nombre==1)
		return $sg;
		
		
	else
		return $pl;

}
function get($info){


	if(is_membre()){
	
		if($info=="id"){
			return intval($_SESSION['donnees_membre']['id']);		
		}
		
		elseif($info=="pseudo"){
			return $_SESSION['donnees_membre']['pseudo'];		
		}
		elseif($info=="pass"){
			return $_SESSION['donnees_membre']['pass'];		
		}
		elseif($info=="reglement"){
			return $_SESSION['donnees_membre']['reglement'];		
		}
	
		else
			return false;
	
	}
	
	
	else
		return false;




}


function getEtat($donnees, $info, $mode=null){

	if(!is_array($donnees))
		return false;
		
	if(!isset($donnees['service_'.$info]))
		return false;
		
	if($mode==1){
		if($donnees['service_'.$info]=="oui"){
			return "oui";
		} else {
			return "non";
		}
	
	
	}
		
	if($donnees['service_'.$info]=="oui"){
		return "<span style='color: green;'>Activ&eacute;</span>";
	} else {
		return "<span style='color: red;'>Off</span>";
	}

}


function ConvertTime($datetime,$output) {
  $explode = explode(' ',$datetime);
  $date = $explode[0];
  $time = $explode[1];
  
  switch($output) {
  
    case 'fr' :
	
	  list($annee,$mois,$jour) = explode('-', $date);
	  list($heure,$minutes,$secondes) = explode(':', $time);
	  
  	  return ($jour.'/'.$mois.'/'.$annee.' à '.$heure.':'.$minutes.':'.$secondes);
	  
	  break;
	  
	case 'mysql' :
	
	  list($annee,$mois,$jour) = explode('/', $date);
	  list($heure,$minutes,$secondes) = explode(':', $time);
	  
  	  return ($annee.'-'.$mois.'-'.$jour.' à '.$heure.':'.$minutes.':'.$secondes);
	  
	  break;
	  
  }
}

function Pagination($max,$nb,$num,$links) {

    $barre = '';
    
    if ($_SERVER['QUERY_STRING'] == '') {
        $query = $_SERVER['PHP_SELF'].'?num=';
    }
    else {
        $tableau = explode ('num=',$_SERVER['QUERY_STRING']);
        $nb_element = count($tableau);
        if ($nb_element == 1) {
            $query = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&num=';
        }
        else {
            if ($tableau[0] == '') {
                $query = $_SERVER['PHP_SELF'].'?num=';
            }
            else {
                $query = $_SERVER['PHP_SELF'].'?'.$tableau[0].'num=';
            }
        }
    }
    
    $page_active = floor(($num/$nb)+1);
    $nb_pages_total = ceil($max/$nb);
    
    if ($links%2==0) {
        $cpt_deb1 = $page_active - ($links/2)+1;
        $cpt_fin1 = $page_active + ($links/2);
    }
    else {
        $cpt_deb1 = $page_active - floor(($links/2));
        $cpt_fin1 = $page_active + floor(($links/2));
    }
    
    if ($cpt_deb1 <= 1) {
        $cpt_deb = 1;
        $cpt_fin = $links;
    }
    elseif ($cpt_deb1>1 && $cpt_fin1<$nb_pages_total) {
        $cpt_deb = $cpt_deb1;
        $cpt_fin = $cpt_fin1;
    }
    else {
        $cpt_deb = ($nb_pages_total-$links)+1;
        $cpt_fin = $nb_pages_total;
    }

    if ($nb_pages_total <= $links) {
        $cpt_deb=1;
        $cpt_fin=$nb_pages_total;
    }
    
    if ($cpt_deb != 1) {
        $cible = $query.(0);
        $lien = '<a href="'.$cible.'">&lt;&lt;</a>&nbsp;&nbsp;...&nbsp;&nbsp;';
    }
    else {
        $lien='';
    }
    $barre .= $lien;

    for ($cpt = $cpt_deb; $cpt <= $cpt_fin; $cpt++) {
        if ($cpt == $page_active) {
            if ($cpt == $nb_pages_total) {
                $barre .= '[<b>'.$cpt.'</b>]';
            }
            else {
                $barre .= '[<b>'.$cpt.'</b>]&nbsp;';
            }
        }
        else {
            if ($cpt == $cpt_fin) {
                $barre .= '<a href="'.$query.(($cpt-1)*$nb);
                $barre .= '">'.$cpt.'</a>';
            }
            else {
                
                $barre .= '<a href="'.$query.(($cpt-1)*$nb);
                $barre .= '">'.$cpt.'</a>&nbsp;';
            }
        }
    }
    
    $fin = ($max - ($max % $nb));
    if (($max % $nb) == 0) {
        $fin = $fin - $nb;
    }


    if ($cpt_fin != $nb_pages_total) {
        $cible = $query.$fin;
        $lien = '&nbsp;&nbsp;...&nbsp;&nbsp;<a href="'.$cible.'">&gt;&gt;</a>';
    }
    else {
        $lien='';
    }
    $barre .= $lien;

    return $barre;    
}

function strClean($var)
{
    if(is_numeric($var))
        return $var;
    elseif(is_array($var))
    {
        foreach($var as $k => $v)
            $var[$k] = strClean($v);
    }
    else
    {
        if(get_magic_quotes_gpc())
            $var = stripslashes($var);

        $var = mysql_real_escape_string($var);
    }
    return htmlentities($var);
}


function isColor($color){
	 $return =false;
	 
	 if (preg_match('/^[a-fd0-9A-FD]{6}$/i', $color)) {
		$return =true;
	 }
	 
	 return $return;
 }
 
 function isBildberg($lien){
	 $return =false;
	 
	 if (empty($lien) or filter_var($lien, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED | FILTER_FLAG_SCHEME_REQUIRED ) && preg_match('#\.(jpg|png|gif|bmp)$#i', $lien)) {
		$return=true;
	 }
	 
	 return $return;
 }
 
 
 function bildberg_file($url)
{
	if(!@fopen($url, 'r')) 
		return false;
	else 
		return true;
}
 
 
 
 
function chargerClasse($nom){
	
	return require_once(SROOT."/class/$nom.class.php");
}

spl_autoload_register("chargerClasse");