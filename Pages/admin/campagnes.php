<?php 

if(!is_admin()){
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
		
		$retour_campagnes = Query("SELECT * FROM publicites ORDER BY id");
		
		
		?>
		<br />
		<table>
		
			<thead>
				<tr>
					<th>Nom</th>
					<th>Statut</th>
					<th>Texte de campagne</th>
					<th>Dates</th>
				
				</tr>
			</thead>
			
			
			<tbody>
				<?php 
				if(mysql_num_rows($retour_campagnes)==0){?>
				
					<tr class="pair">
						<td colspan="4">
						
							Il n'y a aucune campagne pour le moment
						</td>
					
					</tr>
				<?php 
				} else {
					$debut="impair";
					while($donnees=mysql_fetch_array($retour_campagnes)){
						if($debut=="impair")
							$debut = "pair";
						else
							$debut = "impair";
							
							
							
						$time=time();
						if($time>=$donnees['debut_campagne'] && $time<=$donnees['fin_campagne'])
							$statut = "<span class='cours'>En cours</span>";
						
						elseif($time>$donnees['fin_campagne'])
							$statut = "<span class='fini'>Termin&eacute;</span>";
						
						elseif($time<$donnees['debut_campagne'])
							$statut = "<span class='prochainement'>Prochainement</span>";
						else
							$statut = "<span class='erreur'>Erreur</span>";
					?>
						<tr class="<?=$debut;?>">
							<td>
								<a href="/admin/campavoir/<?=$donnees['id'];?>"><?=$donnees['nom'];?></a>
							</td>
							
							<td>
								<?=$statut;?>
							</td>
							
							<td>
								<a href="<?=$donnees['url'];?>" target="_blank"><?=$donnees['texte'];?></a>
							</td>
							
							<td>
								<?=converseDate($donnees['debut_campagne'], 10);?> au <?=converseDate($donnees['fin_campagne'], 10);?>
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
						
							<a href="/admin/campajoute">Ajouter une campagne</a>
						
						</td>
					
					</tr>
				
			</tfoot>
			
			
			
		
		</table>
		
		
   </div>
<?php 
require_once('templates/bas.php');


