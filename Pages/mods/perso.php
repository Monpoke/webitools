<?php 

$id_site = intval(getParam(2));
$module = getParam(1);



if(!in_array($module, $modules_persos))
	Redirect("/panel");

if(!is_modo()){
	$cond = "sites.id_membre='".get('id')."'";
	
} else {
	
	if(is_modo(true))
		$cond = "membres.rang < '".MODERATEUR."'";
	else
		$cond = "membres.rang<='".ADMINISTRATEUR."'";
		
}

$retour_site = Query("SELECT * FROM sites, membres WHERE sites.id_site='$id_site' and $cond and sites.etat='2' and membres.id = sites.id_membre");

if(mysql_num_rows($retour_site)!=1)
	Redirect("/panel");

	
$donnees=mysql_fetch_array($retour_site);


if(getEtat($donnees, $module, 1)=="non")
	Redirect("/website/gerer/$id_site");



$titre = "Personnalisation";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Personnalisation</h3>
	   <br />
	   
	   <?php 
	   
	   
	   if($module=="inscription"){
			require('fichiers/inscription.php');
		}elseif($module=="connexion"){
			require('fichiers/connexion.php');
		}elseif($module=="liensjours"){
			require('fichiers/liensjours.php');
		}elseif($module=="tchat"){
			require('fichiers/tchat.php');
		}
		else {
			echo "Bug en attente";
		}?>
	   
	   <br /><br />
	   <a href="/website/gerer/<?=$id_site;?>">Retour</a>
   </div>
<?php 

require_once('templates/bas.php');


