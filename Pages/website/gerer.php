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

$titre = "G&eacute;rer un site";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>G&eacute;rer un site : <?=$donnees['nom'];?></h3>
	   <br />
	   <br />
		<table>
		
			<thead>
				<tr>
					<th style="width: 180px">Nom du module</th>
					<th>&Eacute;tat</th>
					<th>Actions</th>
				
				</tr>
			</thead>
			
			
			<tbody>
				<?php if(is_developper()){?>
				<tr class="impair">
					<td>
						Vocabulaire du site
					</td>
					
					<td>
						-
					</td>
					
					<td>
						
							<a href="/mods/vocab/<?=$id_site;?>">Personnaliser</a><br />
						
					</td>
					
					
				
				</tr>
				<?php }?>
				<tr class="pair">
					<td>
						Inscription personalis&eacute;e
					</td>
					
					<td>
						<?=getEtat($donnees, 'inscription');?>
					</td>
					
					<td>
						<?php 
						if(getEtat($donnees, 'inscription', 1)=="non"){?>
							<a href="/mods/activate/inscription/<?=$id_site;?>">Activer</a>
						<?php 
						} else {
						?>
							<a href="/mods/perso/inscription/<?=$id_site;?>">Personnaliser</a><br />
							<a href="/mods/stats/inscription/<?=$id_site;?>">Stats</a><br />
							<a href="/mods/activate/inscription/<?=$id_site;?>">D&eacute;sactiver</a>
						<?php } ?>
					</td>
					
					
				
				</tr>
				
				<tr class="impair">
					<td>
						Connexion personalis&eacute;e
					</td>
					
					<td>
						<?=getEtat($donnees, 'connexion');?>
					</td>
					
					<td>
						<?php 
						if(getEtat($donnees, 'connexion', 1)=="non"){?>
							<a href="/mods/activate/connexion/<?=$id_site;?>">Activer</a>
						<?php 
						} else {
						?>
							<a href="/mods/perso/connexion/<?=$id_site;?>">Personnaliser</a><br />
							<a href="/mods/stats/connexion/<?=$id_site;?>">Stats</a><br />
							<a href="/mods/activate/connexion/<?=$id_site;?>">D&eacute;sactiver</a>
						<?php } ?>
					</td>
					
					
				
				</tr>
				
				<tr class="pair">
					<td>
						Vote Webmaster
					</td>
					
					<td>
						<?=getEtat($donnees, 'vote');?>
					</td>
					
					<td>
						<?php 
						if(getEtat($donnees, 'vote', 1)=="non"){?>
							<a href="/mods/activate/vote/<?=$id_site;?>">Activer</a>
						<?php 
						} else {
						?>
							<a href="/mods/stats/vote/<?=$id_site;?>">Stats</a><br />
							<a href="/mods/activate/vote/<?=$id_site;?>">D&eacute;sactiver</a>
						<?php } ?>
					</td>
					
					
				
				</tr>
			
				<tr class="impair">
					<td>
						Liens du jour
					</td>
					
					<td>
						<?=getEtat($donnees, 'liensjours');?>
					</td>
					
					<td>
						<?php 
						if(getEtat($donnees, 'liensjours', 1)=="non"){?>
							<a href="/mods/activate/liensjours/<?=$id_site;?>">Activer</a>
						<?php 
						} else {
						?>
							<a href="/mods/perso/liensjours/<?=$id_site;?>">Personnaliser</a><br />
							<a href="/mods/stats/liensjours/<?=$id_site;?>">Stats</a><br />
							<a href="/mods/activate/liensjours/<?=$id_site;?>">D&eacute;sactiver</a>
						<?php } ?>
					</td>
					
					
				
				</tr>
				
				<tr class="pair">
					<td>
						Tchat
					</td>
					
					<td>
						<?=getEtat($donnees, 'tchat');?>
					</td>
					
					<td>
						<?php 
						if(getEtat($donnees, 'tchat', 1)=="non"){?>
							<a href="/mods/activate/tchat/<?=$id_site;?>">Activer</a>
						<?php 
						} else {
						?>
							<a href="/mods/perso/tchat/<?=$id_site;?>">Personnaliser</a><br />
							<a href="/mods/stats/tchat/<?=$id_site;?>">Stats</a><br />
							<a href="/mods/activate/tchat/<?=$id_site;?>">D&eacute;sactiver</a>
						<?php } ?>
					</td>
					
					
				
				</tr>
			</tbody>
			
		</table>
		<br />
		
		<u><a href="/mods/cache/<?=$id_site;?>" onclick="return(confirm('Confirmer le vidage du cache ?'))" title="Vider le cache ?">Quota du cache : </a></u> <b><?=$donnees['quota_cache'];?></b> / <b>10</b>.
		
		<br />
		
		<small><i>Le cache est un syst&egrave;me de sauvegardes temporaires des pages de votre site. Cela permet d'alleger les requ&ecirc;tes du site, et permet par cons&eacute;quent d'acc&eacute;lerer l'affichage et r&eacute;duire la charge serveur. Vous pouvez vider le cache 10 fois par jour. N'abusez pas de cette fonction ou le quota sera baiss&eacute;.<br />Merci :)</i></small>
		
		
		
		<br /><br />
		<a href="/panel">Retour au panel</a>
   </div>
<?php 
require_once('templates/bas.php');


