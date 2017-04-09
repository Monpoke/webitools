<?php 

if(!is_modo()){
	Redirect("/membre/connexion");
}


$titre = "Panel";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Panel de gestion</h3>
	   
		
		
		<!-- START TO SHOW ALL WEBIDEV'S WEBSITE -->
		<?php 
		if(is_modo(true))
			$cond = "membres.rang < '".MODERATEUR."'";
		else
			$cond = "membres.rang<='".ADMINISTRATEUR."'";
			
		$retour_membres = Query("SELECT * FROM membres, sites WHERE $cond and sites.id_membre=membres.id ORDER BY sites.id_membre ASC, sites.id_site DESC ");
		
		
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
						
							Il n'y a aucun site
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
		
		<br /><br />
		
		<a href="/admin">Retour</a>
		
   </div>
<?php 
require_once('templates/bas.php');


