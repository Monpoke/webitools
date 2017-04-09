<?php 

if(!is_modo()){
	Redirect("/membre/connexion");
}


$titre = "Panel";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Panel de gestion</h3>
	   
		<?php 
		if(getParam(0)=="cook"){
		?>
			<p class="confirm">
				Vous vous &ecirc;tes connect&eacute; avec succ&egrave;s !
			</p>
		<?php
		}?>
		
		<!-- START TO SHOW ALL WEBIDEV'S WEBSITE -->
		<?php 
		if(is_modo(true))
			$cond = "membres.rang < '".MODERATEUR."'";
		else
			$cond = "membres.rang<='".ADMINISTRATEUR."'";
			
		$retour_membres = Query("
SELECT DISTINCT membres.*, COUNT(sites.nom) AS nombre_site
FROM membres
LEFT JOIN sites
ON membres.id = sites.id_membre
GROUP BY membres.id
ORDER BY membres.id
");
		
		?>
		<br />
		<table>
		
			<thead>
				<tr>
					<th>Pseudo</th>
					<th>Derni&egrave; connexion</th>
					<th>Nombre de sites</th>
					<th>Charte lue</th>
				
				</tr>
			</thead>
			
			
			<tbody>
				<?php 
				$nbre_membres=mysql_num_rows($retour_membres);
				$avec=0;
				$total_sites=0;
				$reglement=0;
				
				if($nbre_membres==0){?>
				
					<tr class="pair">
						<td colspan="4">
						
							Il n'y a aucun membre
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
								<?=converseDate($donnees['derniere_connexion']);?>
							</td>
							
							<td>
								<?php
								// $retour_sites = Query("SELECT * FROM sites WHERE etat>'1' and id_membre='{$donnees['id']}'");
								
								// $nombre=mysql_num_rows($retour_sites);
								
								$nombre=$donnees['nombre_site'];
								echo genPluriel($nombre, "1 site", "{nbre} sites", "Aucun");
								if($nombre>=1){
									$avec++;
									$total_sites+=$nombre;
								}
								?>
							</td>
							
							<td>
								<?php 
								if($donnees['reglement']=="oui"){
									$reglement++;
								?>
									<span class="confirm">Oui</span>
								<?php 
								} else { ?>
									<span class="erreur">NON</span>
								<?php }?>
							</td>
							
						</tr>
				
					<?php
					}
				}
				?>
				
				
			
			
			
			</tbody>
			
		
			
			
			
		
		</table>
		<br />
		<?php
		
		echo $nbre_membres." membres<br />";
		echo " dont  $avec membres ayant au moins un site (au total $total_sites sites)<br />";
		
		$p = round(($avec/$nbre_membres)*100);
		echo "Ce qui fait $p%";
		?>
		<br /><br />
		<?=$reglement;?> lectures accept&eacute;es des rules.
   </div>
<?php 
require_once('templates/bas.php');


