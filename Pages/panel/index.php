<?php 

if(!is_membre()){
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
		}
		
		// LE REGLEMENT DOIT ETRE ACCEPTE
		if(get('reglement')=="non") {
		?>
			<p>
				La charte du site s'est simplifi&eacute;e et a &eacute;t&eacute; modifi&eacute;e ! <br />
				Pour poursuivre, vous devez <a href="/cgu">lire la charte</a>.<br /><br />
			</p>
			
			<p class="confirm"><a href="/membre/chartok">J'accepte la nouvelle charte</a></p>
			
			<p>
				<br /><br />
				<i>Si vous n'acceptez pas le r&eacute;glement avant le 25/10/2012, vos services seront suspendus.</i>
			
			</p>
		<?php
		}
		
		else {
		
		
		?>
		
		<!-- START TO SHOW ALL WEBIDEV'S WEBSITE -->
		<?php 
		$retour_sites = Query("SELECT * FROM sites WHERE id_membre='".get('id')."'");
		
		
		?>
		<br />
		<table>
		
			<thead>
				<tr>
					<th style="width: 180px">Nom du site</th>
					<th>&Eacute;tat</th>
					<th>Modules</th>
					<th>Actions</th>
				
				</tr>
			</thead>
			
			
			<tbody>
				<?php 
				if(mysql_num_rows($retour_sites)==0){?>
				
					<tr class="pair">
						<td colspan="4">
						
							Vous n'avez encore ajout&eacute; aucun site !
						
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
								<a target="_blank" href="http://webidev.com/<?=urlencode($donnees['namespace']);?>"><?=$donnees['nom'];?></a>
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
								<?=genPluriel($donnees['nbre_modules'], '1', '%nbre%', 'Aucun');?>
							</td>
							
							<td>
								<?php 
								if($donnees['etat']=='1'){?>
									<a href="/website/valider/<?=$donnees['id_site'];?>">Valider le site</a><br />
								<?php } else {?>
									<a href="/website/gerer/<?=$donnees['id_site'];?>">G&eacute;rer</a><br />
								<?php }?>
								
								<a href="/website/supprimer/<?=$donnees['id_site'];?>" onclick="return(confirm('Confirmer ?')); return false;">Supprimer</a>
							
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
						
							<a href="/website/ajouter">Ajouter un site Webidev</a>
						
						</td>
					
					</tr>
				
			</tfoot>
			
			
			
		
		</table>
		<?php }?>
		
   </div>
<?php 
require_once('templates/bas.php');


