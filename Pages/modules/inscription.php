<?php 

$id_site = intval(getParam(1));
$pseudo_parrain = $pseudo_parrain2 = getParam(2);


$retour_site = Query("SELECT * FROM sites, membres WHERE sites.id_site='$id_site' and sites.etat='2' and membres.id = sites.id_membre");

if(mysql_num_rows($retour_site)!=1){
	Redirect("/", 5);
	$titre="Module d'inscription";
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
$conf=mysql_fetch_array(Query("SELECT * FROM inscriptions WHERE id_site='$id_site'"));


if($donnees['service_inscription']=='non'){
Redirect("/", 5);
$titre="Module d'inscription";
require('templates/haut_mod.php');
?>
		
	<div class="bloc_erreur">
		Le webmaster du dit-site n'a pas activ&eacute; ce module.<br /><br />
		
		<a href="/" onclick="javascript:history.go(-1)">Retour</a>
	</div>
		
		
<?php 
require('templates/bas_mod.php');
exit;
} 


$com="";
if(getParam(2)=="preview" && ($donnees['id_membre']==get('id') or is_modo())){
	$com = "/preview";
}

elseif(!empty($pseudo_parrain2)){
	$com = "/$pseudo_parrain2";
}
if($conf['type']==2){
	Redirect("/modules/inscriptionv2/{$id_site}{$com}");
}


// VERIF SITES FERMES OU INSCRIPTIONS FERMES
$ferme = false;

$retour_fermeture = @file_get_contents('http://www.webidev.com/'.$donnees['namespace'].'/MInscription');

if($retour_fermeture==false) {
	Redirect("/modules/inscription/".$donnees['id_site'].$com);
}
elseif(preg_match("#Retour sur Webidev#", $retour_fermeture) && empty($com)){
	$ferme=true;
	$origine = "ferme";
}
elseif(!preg_match("#form#", $retour_fermeture) && empty($com)){
	$ferme=true;
	$origine = "inscription";
}




if(isset($_POST['ok']) && !$ferme){

	$erreur = false;
	$message = "";
	
	
	if(empty($_POST['Pseudo']) or strlen($_POST['Pseudo']) > 20){
		$erreur = true;
		$message .= "Le pseudo ne peut-&ecirc;tre vide !"."<br />";
	} else { // verif utilise
		// Submit those variables to the server
		$post_data = array(
			'Pseudo' => $_POST['Pseudo'],
			'MembreInscription' => "Valider"
		);
		 
		// Send a request to example.com 
		$result = post_request('http://www.webidev.com/'.urlencode($donnees['namespace']).'/MInscription', $post_data);
		 
		if ($result['status'] == 'ok'){
		 
			// print the result of the whole request:
			if(preg_match("#Merci#", $result['content'])){
				$erreur = true;
				$message .= "Le pseudo est d&eacute;j&agrave; utilis&eacute; !"."<br />";
			} 
		 
		} 
	
	}
	
	if(empty($_POST['Password']) or strlen($_POST['Password']) > 20 or strlen($_POST['Password']) < 5  ){
		$erreur = true;
		$message .= "Le mot de passe doit comporter 5 caract&egrave;res minimum !"."<br />";
	} elseif($_POST['Password'] != $_POST['Password2']){
		$erreur = true;
		$message .= "Les mots de passe sont diff&eacute;rents !"."<br />";
	}
	
	if(empty($_POST['Email']) or !filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL)){
		$erreur = true;
		$message .= "L'email est invalide !"."<br />";
	} 
	
	if(empty($_POST['JourNez']) or is_nan($_POST['JourNez']) or $_POST['JourNez'] > 31 or  $_POST['JourNez'] < 1 or empty($_POST['MoisNez']) or is_nan($_POST['MoisNez']) or $_POST['MoisNez'] > 12 or  $_POST['MoisNez'] < 1 or empty($_POST['AnneeNez']) or is_nan($_POST['AnneeNez']) or $_POST['AnneeNez'] > date('Y') or  $_POST['AnneeNez'] < 1900){
		$erreur = true;
		$message .= "La date de naissance est invalide !"."<br />";
	} 
	
	if(empty($_POST['Sexe']) or ($_POST['Sexe'] != "M" && $_POST['Sexe'] != "F")){
		$erreur = true;
		$message .= "Le sexe est invalide !"."<br />";
	} 
	
	
	// Pas d'erreur
	if($erreur==false){
		
		
		if($com!="/preview"){
			// Submit those variables to the server
			$post_data = array(
				
				'MembreInscription' => "Valider"
			);
			 
			 foreach($_POST as $valeur => $val){
			 
				$post_data[$valeur] = $val;
			 }
			 
			 
			 	 
			 // c'est que tout est bon, on enregistre le parrain si il y a besoin
			 if(!empty($pseudo_parrain2) && $pseudo_parrain2!="preview"){
				
				$pseudo_parrain2 = Secure($pseudo_parrain2);
				$pseudo_filleuil = Secure(chiffre($_POST['Pseudo']));
				// on enregistre les relations
				Query("INSERT INTO parrainages(id_site, pseudo_parrain, pseudo_filleuil, date) VALUES('$id_site', '$pseudo_parrain2', '$pseudo_filleuil', '".time()."')");
			 
			 }
			 
			// Send a request to example.com 
			$result = post_request('http://www.webidev.com/'.$donnees['namespace'].'/MInscription', $post_data);
			 
			if ($result['status'] == 'ok'){
				// print the result of the whole request:
				if(preg_match("#Vous pouvez#", $result['content'])){
					$ok = true;
					$message_ok = "Bravo ! Vous pouvez vous connecter maintenant !"."<br />";
					
					$nouveau = $donnees['inscrits']+1;
					Query("UPDATE sites SET inscrits='$nouveau' WHERE id_site='$id_site'");
				} else {
					$erreur_fatale=true;
					$message = "Une erreur est survenue ! <b>Code A56</b>";
				}
			 
			} else {
				$erreur_fatale=true;
				$message = "Une erreur est survenue ! <b>Code B86</b><br /> Le probl&egrave;me a &eacute;t&eacute; signal&eacute; ! <br />";
				
				if(is_developper())
					$message .= $result['error'];
				
				
				ob_start();
				print_r($_SERVER);
				print_r($_POST);
				$contenu=ob_get_clean();
				
				mail('dragonralph@gmail.com', 'webiTools', 'CodeB86 '.$contenu);
			}			
			
		
		} else {
			$ok = true;
			$message_ok = "Bravo ! Vous pouvez vous connecter maintenant !"."<br />";
		
		}
		
			
	
	}
	
	
	
	
}



$titre=$donnees['nom'];
require('templates/haut_mod2.php');
?>
	<div id="centre">
		<div id="conteneur_header">
			<!-- header image -->
			<?php 
			if(!empty($conf['champ_header'])){?>
			
				<a href="http://webidev.com/<?=$donnees['namespace'];?>" title="Retour"><img src="<?=$conf['champ_header'];?>" alt="Inscription" /></a>
			<?php }?>
		</div>
		
		<div id="contenu">
			
		
			<form action="/modules/inscription/<?=$id_site.$com;?>" method="post">
				<?php 
				if(isset($erreur)&&$erreur){
				?>
					<p class="erreur">
						<?=$message;?>
					
					</p>
				<?php
				} 
				if(!isset($erreur_fatale) && (!isset($ok) or !$ok) && !$ferme){
					
					
					// on configure les textes ici
					if(empty($conf['texte_pseudo']) or $conf['texte_pseudo'] == "@1"){
						$texte_pseudo = "Pseudo";
					} else {
						$texte_pseudo = $conf['texte_pseudo'];
					}
					
					if(empty($conf['texte_pass1']) or $conf['texte_pass1'] == "@1"){
						$texte_pass1 = "Mot de passe";
					} else {
						$texte_pass1 = $conf['texte_pass1'];
					}
					
					if(empty($conf['texte_pass2']) or $conf['texte_pass2'] == "@1"){
						$texte_pass2 = "Confirmer votre mot de passe";
					} else {
						$texte_pass2 = $conf['texte_pass2'];
					}
					
					if(empty($conf['texte_email']) or $conf['texte_email'] == "@1"){
						$texte_email = "Email";
					} else {
						$texte_email = $conf['texte_email'];
					}
					
					if(empty($conf['texte_date']) or $conf['texte_date'] == "@1"){
						$texte_date = "Date de naissance (jj-mm-aaaa)";
					} else {
						$texte_date = $conf['texte_date'];
					}
					
					if(empty($conf['texte_sexe']) or $conf['texte_sexe'] == "@1"){
						$texte_sexe = "Sexe";
					} else {
						$texte_sexe = $conf['texte_sexe'];
					}
					
					if(empty($conf['texte_btn_inscrire']) or $conf['texte_btn_inscrire'] == "@1"){
						$texte_inscrire = "S'inscrire";
					} else {
						$texte_inscrire = $conf['texte_btn_inscrire'];
					}
					
					$parrain="";
	
					if(is_developper() && !empty($pseudo_parrain) && $pseudo_parrain != "preview"){
						
						$pseudo_parrain = dechiffre($pseudo_parrain);
						
						$parrain = "Pseudo du parrain :<i> <a href='#csexe' style='cursor: default'>$pseudo_parrain</a></i><br />";
					
					}
					
				?>
					<table>
						<tr>	
							<td class="td">
								<label for="cpseudo"><?=$texte_pseudo;?> :</label>
							</td>
							<td>
								<input type="text" name="Pseudo" id="cpseudo" class="perso" maxlength="20" size="18" value="<?=input_value('', 'Pseudo');?>" />
							</td>
						</tr>
						<tr>	
							<td class="td">
								<label for="cpass"><?=$texte_pass1;?> :</label>
							</td>
							<td>
								<input type="password" name="Password" id="cpass" class="perso" maxlength="16" size="18" value="" />
							</td>
						</tr>
						<tr>	
							<td class="td">
								<label for="cconfirm"><?=$texte_pass2;?> :</label>
							</td>
							<td>
								<input type="password" name="Password2" id="cconfirm" class="perso" maxlength="16" size="18" value="" />
							</td>
						</tr>
						<tr>	
							<td class="td">
								
								<label for="cemail"><?=$texte_email;?> : </label>
							</td>
							<td>
								<input type="email" name="Email" id="cemail"  class="perso" maxlength="50" value="<?=input_value('', 'Email');?>" />
							</td>
						</tr>
						<tr>	
							<td class="td">
								<label for="cdate"><?=$texte_date;?> :</label>
							</td>
							<td>
							
								<input type="text" class="perso" name="JourNez" size="2" maxlength="2" value="<?=input_value('', 'JourNez');?>" />
								
								<input type="text" class="perso" name="MoisNez" size="2" maxlength="2" value="<?=input_value('', 'MoisNez');?>" />
								
								<input type="text" class="perso" name="AnneeNez" size="4" maxlength="4" value="<?=input_value('', 'AnneeNez');?>" />
								
							</td>
						</tr>
						<tr>	
							<td class="td">
								<label for="csexe"><?=$texte_sexe;?> :</label>
							</td>
							<td>
								<select class="perso" name="Sexe" id="csexe">
								   <option value="">-------------------</option>
								   <option value="M">Masculin</option>
								   <option value="F">F&eacute;minin</option>
							   </select>
							
							</td>
						</tr>
					</table>
					
					<?=$parrain;?>
					<input type="submit" name="ok" value="<?=$texte_inscrire;?> " />
				
				<?php } elseif($ferme){?>
				
					<p class="erreur">
						<?php 
						if($origine=="ferme"){?>
							Le site est momentan&eacute;ment ferm&eacute; !<br /><br />
							<a href="http://webidev.com/?Parrain=monpoke" class="go">Retour sur Webidev</a>
						
						<?php } else { ?>
							Les inscriptions sont temporairements ferm&eacute;es !<br /><br />
							<a href="http://webidev.com/<?=$donnees['namespace'];?>" class="go">Retour &agrave; l'accueil</a>
						
						<?php }?>
					</p>
				
				
				<?php } elseif(isset($erreur_fatale)){?>
				
					<p class="erreur">
						<?=$message;?>
						<br />
					</p>
					</form>
					<form action="<?="http://www.webidev.com/".$donnees['namespace']."/MInscription";?>" method="post">
					
						<?php 
						foreach($_POST as $nom => $valeur){?>
							
						<input type="hidden" name="<?=$nom;?>" value="<?=$valeur;?>" />
						
						<?php } ?>
						<input type="hidden" name="MembreInscription" value="Valider" />
						<input type="submit" value="Vous pouvez cliquer ici" />
					
				
				
				<?php } else { ?>
					<p class="confirm">
						<?=$message_ok;?><br /><br />
						
						<?php 
						if($donnees['type']!='demo'){
						
							$truc="";
							if(!empty($conf['champ_30']))
								$truc="/ViewPage?Id=".$conf['champ_30'];
								
						?>
							<a href="http://webidev.com/<?=$donnees['namespace'].$truc;?>" class="go">Cliquez ici !</a>
						<?php } else {?>
							<a href="#" onclick="alert('Version de d&eacute;monstration')" class="go">Cliquez ici !</a>
						<?php } ?>
					</p>
				<?php } ?>
			</form>
		
		
		
		</div>
		<?php 
		if($donnees['pub']=="oui" && PUB_PLUME){?>
			<div id="pub"><a href="http://plumedor.fr">Pub : <b>Plume d'Or</b> ouvre en <b>b&ecirc;ta-test</b></a></div>
	
		<?php }elseif($donnees['pub']=="oui"&&empty($com)){?>
			<div id="pub"><?=PUBLICITE;?></div>
			
		<?php } elseif(!empty($com) && $com == "/preview"){?>
			<div id="pub">
				<b>Note importante : En mode aper&ccedil;u, les donn&eacute;es ne sont pas envoy&eacute;es !</b>
			</div>
		<?php } elseif($donnees['type']=='demo'){?>
			<div id="pub">
				Version de d&eacute;monstration<br />
				Les images appartiennent &agrave; leur auteurs respectifs.
			</div>
		<?php } ?>
	</div>
<?php 
require('templates/bas_mod.php');