<?php 

if(!is_modo()){
	Redirect("/membre/connexion");
}

$id=intval(getParam(1));

if(is_modo(true))
	$cond = "membres.rang < '".MODERATEUR."'";
else
	$cond = "membres.rang<='".ADMINISTRATEUR."'";
	
$retour = Query("SELECT * FROM membres WHERE id='$id' and $cond");


if(mysql_num_rows($retour)==0)
	Redirect("/");

	
$membre = mysql_fetch_array($retour);
	
$titre = "Panel";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Voir un membre : <?=$membre['pseudo'];?></h3>
	   
		
		
		<!-- START TO SHOW ALL WEBIDEV'S WEBSITE -->
		<?php 
		
		$retour_sites = Query("SELECT * FROM sites WHERE id_membre='$id'");
		
		
		?>
		<br />
		
		<b>Pseudo :</b> <?=$membre['pseudo'];?><br />
		<?php 
		if(is_admin()){?>
			<b>Ip :</b> <?=$membre['ip'];?><br  />
			
		<?php }?>
		<b>Inscription le </b> : <?=converseDate($membre['date_inscription']);?><br />
		<b>Derni&egrave; connexion</b> : <?=converseDate($membre['derniere_connexion']);?><br /><br />
		
		
		<table>
		
			<thead>
				<tr>
					<th style="width: 180px">Nom</th>
					<th>&Eacute;tat</th>
					<th>Actions</th>
				
				</tr>
			</thead>
			
			
			<tbody>
				<?php 
				if(mysql_num_rows($retour_sites)==0){?>
				
					<tr class="pair">
						<td colspan="3">
						
							Le membre n'a aucun site
						</td>
					
					</tr>
				<?php 
				} else {
					$debut="impair";
					while($donnees=mysql_fetch_array($retour_sites)){
						if($debut=="impair")
							$debut = "pair";
						else
							$debut = "impair";
					?>
						<tr class="<?=$debut;?>">
							<td>
								<a href="http://webidev.com/<?=$donnees['namespace'];?>" target="_blank"><?=$donnees['nom'];?></a>
							</td>
							
							<td>
								<?php 
								if($donnees['etat']=='1'){?>
									<img src="/images/icones/wait.png" alt="En attente de validation" class="help" title="En attente de validation" />
								<?php } else {?>
									<img src="/images/icones/ok.png" alt="Valid&eacute; !" class="help" title="Valid&eacute; !" />
								<?php }?>
							</td>
							
							<td>
								<?php 
								if($donnees['etat']=='1'){?>
									<a href="/website/validera/<?=$donnees['id_site'];?>/<?=$id;?>" onclick="return(confirm('Valider manuellement ?')); return false;">Valider le site</a><br />
								<?php } else {?>
									<a href="/website/gerer/<?=$donnees['id_site'];?>">G&eacute;rer</a><br />
									<a href="/website/supprimer/<?=$donnees['id_site'];?>" onclick="return(confirm('Ok ?')); return false;">Supprimer</a><br />
								<?php } ?>
							</td>
							
							
						</tr>
				
					<?php
					}
				}
				?>
				
				
			
			
			
			</tbody>
			
			<tfoot>
				
					<tr class="ajoutersite">
						<td colspan="4" class="ajouter">
						
							<a href="/website/ajouter/<?=$id;?>">Lui ajouter un site Webidev</a>
						
						</td>
					
					</tr>
				
			</tfoot>
			
			
			
		
		</table>
		
		
   </div>
<?php 
require_once('templates/bas.php');


