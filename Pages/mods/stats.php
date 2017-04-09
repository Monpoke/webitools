<?php 

$id_site = intval(getParam(2));
$module = getParam(1);

if(!in_array($module, $modules_dispos))
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



$titre = "Statistiques";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Stats du module</h3>
	   <br />
	   
	   <?php 
	   if($module=="vote"){
		$retour = Query("SELECT * FROM votes WHERE id_site='$id_site' ORDER BY date DESC");

	   ?>
			<p>
				Pour le moment, <?=genPluriel(mysql_num_rows($retour), '1 webmaster a ', '{nbre} webmasters ont', 'personne n\'a');?> vot&eacute; pour toi.<br /><br />
				
				<?php if(mysql_num_rows($retour)>0){
					while($do=mysql_fetch_array($retour)){?>
						- <a target="_blank" href="http://webidev.com/<?=$do['voteur'];?>"><?=$do['voteur'];?></a> le <?=converseDate($do['date']);?><br />
					
					
					<?php }
					}
				?>
				<br /><br />
				<b><u>Rappel :</u></b><br /><br />
				<code onclick="this.select();">
					&lt;a <?=HTTP_PUBLIC;?>modules/vote/<?=$id_site;?>&gt;Votez pour nous !&lt;/a&gt;
				</code>
				
			</p>
			
	   <?php } elseif($module=="inscription"){

	   ?>
			<p>
				Pour le moment, <?=genPluriel($donnees['inscrits'], '1 membre s\'est', '{nbre} membres se sont', 'personne ne s\'est');?> inscrit sur ton site.<br /><br />
				
				
				<b><u>Rappel :</u></b><br /><br />
				<code onclick="this.select();">
					&lt;a <?=HTTP_PUBLIC;?>modules/parrainage/<?=$id_site;?>&gt;Votre lien de parrainage&lt;/a&gt;
				</code><br /><br /><br /><br />
				
				
				<?php 
				$retour=Query("SELECT * FROM parrainages WHERE id_site='$id_site' ORDER BY date");
				
				if(mysql_num_rows($retour)==0){
					echo "Aucun parrainage pour le moment.<br /><br />";
				
				} else {
				
					$parrains = array();
					
					echo "<u><b>Parrainages :</b></u><br />";
					
					while($m = mysql_fetch_array($retour)){
						
						$parrains[$m['pseudo_parrain']][] = $m['pseudo_filleuil']."=".$m['date'];
						
						
					}
					
				
					
					foreach($parrains as $parrain2 => $filleuils){
					
						$parrain = dechiffre($parrain2);

						?>
						
						 - <b><?=$parrain;?></b> : 
						 
						 <?php 
						 $i=1;
						 foreach($filleuils as $filleuil){
							$filleuil = explode('=', $filleuil);
							
							$date = $filleuil[1];
							$filleuil = $filleuil[0];
							$filleuil=dechiffre($filleuil);
							
							if($i%2)
								echo "<i>";
							?>
								<span title='<?=converseDate($date);?>'><?=$filleuil;?></span>
							<?php
							if($i%2)
								echo "</i>";
							$i++;
						 }
						 ?>
							<br /><br />
						<?php
					
					}
					
				}
				?>
			</p>
			
	   <?php } elseif($module=="tchat"){
			$retour_messages = Query("SELECT * FROM tchat_messages WHERE id_site='$id_site' and etat='1'");
	   ?>
			<p>
				Il y a actuellement <?=mysql_num_rows($retour_messages);?> messages sur le tchat.<br /><br />
				
				<a href="/mods/purger/tchat/<?=$id_site;?>">Purger le tchat</a>
				
			</p>
			
	   <?php } else {?>
		<p>
			Il n'y a aucun stat &agrave; afficher !
		</p>
	   
	   <?php } ?>
	   
	   <br /><br />
	   <a href="/website/gerer/<?=$id_site;?>">Retour</a>
   </div>
<?php 

require_once('templates/bas.php');


