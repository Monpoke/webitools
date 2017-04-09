<?php 

$id_site = intval(getParam(1));


$retour_site = Query("SELECT sites.*, membres.pub FROM sites, membres WHERE sites.id_membre = membres.id and id_site='$id_site' and etat='2'");

if(mysql_num_rows($retour_site)!=1){
	Redirect("/", 5);
	$titre="Module de parrainage";
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

if($donnees['service_inscription']=='non'){
Redirect("/", 5);
$titre="Module de parrainage";
require('templates/haut_mod.php');
?>
		
	<div class="bloc_erreur">
		Le service d'inscription personnalis&eacute;e est requis.<br /><br />
		
		<a href="/" onclick="javascript:history.go(-1)">Retour</a>
	</div>
		
		
<?php 
require('templates/bas_mod.php');
exit;
} 



elseif($donnees['service_connexion']=='non'){
Redirect("/", 5);
$titre="Module de parrainage";
require('templates/haut_mod.php');
?>
		
	<div class="bloc_erreur">
		Le service de connexion personnalis&eacute;e est requis pour r&eacute;cup&eacute;rer le pseudo du membre.<br /><br />
		
		<a href="/" onclick="javascript:history.go(-1)">Retour</a>
	</div>
		
		
<?php 
require('templates/bas_mod.php');
exit;
} 



if(empty($_SESSION['auth_'.$id_site]['Pseudo']))
	Redirect("/modules/connexion/$id_site/parrainage");


$titre="Module de parrainage";
require('templates/haut_mod.php');
?>

	<div class="bloc_info">
		<img src="/images/icones/aide.png" alt="Aide" style="float: left; margin-right: 15px;" />
		Ton lien de parrainage est : <br />
		
		<input type="text" onclick="this.select()" size="<?=strlen(HTTP_PUBLIC."modules/inscription/$id_site/".chiffre($_SESSION['auth_'.$id_site]['Pseudo']));?>" value="<?=HTTP_PUBLIC;?>modules/inscription/<?=$id_site."/".chiffre($_SESSION['auth_'.$id_site]['Pseudo']);?>" />
		
		<div style="clear: both"></div>
		<?php 
		if(!empty($_SERVER['HTTP_REFERER']) && preg_match("#webidev#", $_SERVER['HTTP_REFERER']))
			$avant = $_SERVER['HTTP_REFERER'];
		else
			$avant = 'javascript: history.go(-1)';
		
		?>
		<img src="/images/icones/retour.png" alt="Retour" style='vertical-align:middle' /> <a href="<?=$avant;?>">Retour</a>
	</div>
			




<?php

if($donnees['pub']=="oui"){



?>
	<div id="pubpa"><?=PUBLICITE;?></div>
	
<?php }elseif($donnees['type']=='demo'){?>
	<div id="pubpa">
		Version de d&eacute;monstration<br />
		Les images appartiennent &agrave; leur auteurs respectifs.
	</div>
<?php } ?>
</div>
<?php 
require('templates/bas_mod.php');