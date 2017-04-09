<?php 


$id_site = intval(getParam(1));


$retour_site = Query("SELECT * FROM sites, membres WHERE sites.id_site='$id_site' and sites.etat='2' and membres.id = sites.id_membre");

if(mysql_num_rows($retour_site)!=1){
	Redirect("/", 5);
	$titre="Module de lien du jour";
	require('templates/haut_mod.php');
	?>
			
		<div class="bloc_erreur">
			Le site en question n'existe pas !<br /><br />
			
			<a href="/" onclick="javascript:history.go(-1)">Retour</a>
		</div>
			
			
	<?php 
	require('templates/bas_mod.php');
	exit;

	
}
	
$donnees=mysql_fetch_array($retour_site);


if($donnees['service_liensjours']=='non'){
Redirect("/", 5);
$titre="Module de lien du jour";
require('templates/haut_mod.php');
?>
		
	<div class="bloc_erreur">
		Le webmaster du dit-site n'a pas activ&eacute; ce module.<br /><br />
		
		<a href="/" onclick="javascript:history.go(-1)">Retour</a>
	</div>
		
		
<?php 
require('templates/bas_mod.php');
exit;
} 

$conf=mysql_fetch_array(Query("SELECT * FROM liensjours WHERE id_site='$id_site'"));


$com="";
if(getParam(2)=="preview" && ($donnees['id_membre']==get('id') or is_modo())){
	$com = "/preview";
}


$cours = $depart = $conf['en_cours'];


foreach($conf as $nom => $valeur){
	if(preg_match("#lien_#", $nom)){
		
		$id=substr($nom, 5);
		
		if($cours==$id){
			if(!empty($conf['lien_'.$id]))
				Redirect($conf['lien_'.$cours]);
			else
				$cours++;
		}
		
	}
	
}

if(!empty($_SERVER['HTTP_REFERER']))
	Redirect($_SERVER['HTTP_REFERER'], 2);

exit("Aucun lien du jour n'a p&ucirc; &ecirc;tre trouv&eacute; !");