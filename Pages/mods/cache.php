<?php 

$id_site = intval(getParam(1));


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



$titre = "Vider le cache";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Vider le cache</h3>
	   <br />
	   
	   <?php 
	   
	   if($donnees['quota_cache']<1){
	   
	   ?>
		   <p class="erreur">
				Vous ne pouvez effectuer cette action : quota manquant !
			</p>
	   
	   <?php 
	   }
	   else {
		
			$cache=new Cache();
			
			$nombre = $cache->deleteCache($id_site);
			
			
	   ?>
		<p class="confirm_ok">
		
			Le cache a &eacute;t&eacute; vid&eacute; ! <?=genPluriel($nombre, "1 fichier supprim&eacute; !", "{nbre} fichiers supprim&eacute;s !", "Aucun fichier supprim&eacute; !");?>
			
			<br /><br />
			
			<?php 
			if($nombre>0){
				$new = $donnees['quota_cache']-1;
				Query("UPDATE sites SET quota_cache='$new' WHERE id_site='$id_site'"); 
			?>
				Votre quota a &eacute;t&eacute; d&eacute;bit&eacute; d'une unit&eacute; ! <?=genPluriel($new, "Il vous reste <i>1</i> unit&eacute;", "Il vous reste {nbre} unit&eacute;s", "Il ne vous reste aucune unit&eacute; !");?>
			<?php 
			} else {
			?>
				Votre quota n'a pas &eacute;t&eacute; d&eacute;bit&eacute; !
			<?php 
			}
			?>
		</p>
	   
	   <?php 
	   }
	   
	   ?>
	   
	   <br /><br />
	   <a href="/website/gerer/<?=$id_site;?>">Retour</a>
   </div>
<?php 

require_once('templates/bas.php');


