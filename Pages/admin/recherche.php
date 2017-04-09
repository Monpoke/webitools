<?php 

if(!is_modo()){
	Redirect("/membre/connexion");
}


$titre = "Panel";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Panel de gestion : Recherche</h3>
	   
		
		<form action="/admin/recherche" method="post">
			
			Pseudo : <input type="text" name="pseudo" value="<?=input_value('', 'pseudo');?>" /><br /><br />
			Nom de site : <input type="text" name="nomsite" value="<?=input_value('', 'nomsite');?>" /><br /><br />
			Namespace : <input type="text" name="namespace" value="<?=input_value('', 'namespace');?>" /><br /><br />
			
			
			<input type="submit" value="Rechercher" name="recherche" />
		</form>
		
		<br /><br />
		
		<!-- START TO SHOW ALL WEBIDEV'S WEBSITE -->
		<?php 
		
		if(isset($_POST['recherche'])){
			if(is_modo(true))
				$cond = "membres.rang < '".MODERATEUR."'";
			else
				$cond = "membres.rang<='".ADMINISTRATEUR."'";
			
			$pseudo = Secure($_POST['pseudo']);
			$nomsite = Secure($_POST['nomsite']);
			$namespace = Secure($_POST['namespace']);
			
			$retour_membres = Query("SELECT * FROM membres, sites WHERE $cond and sites.id_membre=membres.id AND (membres.pseudo LIKE '%$pseudo%' AND sites.nom LIKE '%$nomsite%' AND sites.namespace LIKE '%$namespace%' )ORDER BY sites.id_membre ASC, sites.id_site DESC ");
			
			
			?>
			<br />
			<table>
			
				<thead>
					<tr>
						<th>Pseudo</th>
						<th>Nom du site</th>
						<th>Namespace</th>
						<th>Actions</th>
					
					</tr>
				</thead>
				
				
				<tbody>
					<?php 
					if(mysql_num_rows($retour_membres)==0){?>
					
						<tr class="pair">
							<td colspan="4">
							
								Il n'y a aucun r&eacute;sultat
							</td>
						
						</tr>
					<?php 
					} else {
						$debut="impair";
						while($donnees=mysql_fetch_array($retour_membres)){
							if($debut=="impair")
								$debut = "pair";
							else
								$debut = "impair";
						?>
							<tr class="<?=$debut;?>">
								<td>
									<a href="/admin/membre_voir/<?=$donnees['id'];?>"><?=$donnees['pseudo'];?></a>
								</td>
								
								<td>
									<?=$donnees['nom'];?>
								</td>
								
								<td>
									<a href="http://webidev.com/<?=$donnees['namespace'];?>" target="_blank"><?=$donnees['namespace'];?></a>
								</td>
								
								<td>
									
								</td>
								
							</tr>
					
						<?php
						}
					}
					?>
					
					
				
				
				
				</tbody>
				
			
				
				
				
			
			</table>
		<?php 
		}
		?>
		
   </div>
<?php 
require_once('templates/bas.php');


