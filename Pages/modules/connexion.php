<?php 



$id_site = intval(getParam(1));
$redi = getParam(2);
if($redi=="preview")
	$redi="";


$retour_site = Query("SELECT * FROM sites, membres WHERE sites.id_site='$id_site' and sites.etat='2' and membres.id = sites.id_membre");

if(mysql_num_rows($retour_site)!=1){
	Redirect("/", 5);
	$titre="Module de connexion";
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
$conf=mysql_fetch_array(Query("SELECT * FROM connexions WHERE id_site='$id_site'"));


if($donnees['service_connexion']=='non'){
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

if($conf['type']==2){
	Redirect("/modules/connexionv2/{$id_site}{$com}");
}

$com="";
if(getParam(2)=="preview" && $donnees['id_membre']==get('id')){
	$com = "/preview";
	if(!empty($redi))
		$com.="/$redi";
}
elseif(!empty($redi)){
	$com = "/$redi";

}


// VERIF SITES FERMES OU connexions FERMES
$ferme = false;

$retour_fermeture = @file_get_contents('http://www.webidev.com/'.$donnees['namespace'].'/MConnect');

if($retour_fermeture==false) {
	Redirect("/modules/inscription/".$donnees['id_site'].$com);
}
elseif(preg_match("#Retour sur Webidev#", $retour_fermeture)){
	$ferme=true;
	$origine = "ferme";
}


if(isset($_GET['test']) && !empty($conf['test_pseudo']) && !empty($conf['test_pass'])){

$val1 =array(

	'Pseudo' => $conf['test_pseudo'],
	'Password' => $conf['test_pass'],

);

	$_SESSION['auth_'.$id_site]['Pseudo'] = Secure($val1['Pseudo']);
	$_SESSION['auth_'.$id_site]['Password'] = base64_encode($val1['Password']);

	
?>

<form id="form" action='http://www.webidev.com/<?=$donnees['namespace'].'/MConnect';?>' method="post">
	<?php 
	foreach($val1 as $nom => $val){
	?>
		<input type="hidden" name="<?=$nom;?>" value="<?=$val;?>" />
	<?php
	}?>

		<noscript><input type="submit" value="Cliquez ici !" /></noscript>
	
</form>	

<script type="text/javascript">
	//<![CDATA[
	window.onload = function () {
		document.getElementById('form').submit();
	}
	//]]>
</script>
				
				
<?php
exit;
}

if(isset($_POST['ok']) && !$ferme){

	$erreur = false;
	$message = "";
	
	
	if(empty($_POST['Pseudo']) or strlen($_POST['Pseudo']) > 20){
		$erreur = true;
		$message .= "Le pseudo ne peut-&ecirc;tre vide !"."<br />";
	} 
	
	if(empty($_POST['Password']) or strlen($_POST['Password']) > 20 or strlen($_POST['Password']) < 5  ){
		$erreur = true;
		$message .= "Le mot de passe doit comporter 5 caract&egrave;res minimum !"."<br />";
	} 
	
	
	// Pas d'erreur
	if($erreur==false){
		
		
		if($com!="/preview"){
			// Submit those variables to the server
			$post_data = array(
				
				'Connecter' => "Connecter"
			);
			 
			 foreach($_POST as $valeur => $val){
			 
				$post_data[$valeur] = $val;
			 }
			 
			// Send a request to example.com 
			$result = post_request('http://www.webidev.com/'.$donnees['namespace'].'/MConnect', $post_data);
			 
			if ($result['status'] == 'ok'){
				// print the result of the whole request:
				if(!preg_match("#Le pseudo ou le mot de passe n'est pas correct.#", $result['content'])){
					$ok = true;
					
					
					$_SESSION['auth_'.$id_site]['Pseudo'] = Secure($_POST['Pseudo']);
					$_SESSION['auth_'.$id_site]['Password'] = base64_encode($_POST['Password']);
					
					if(!empty($redi)){
						Redirect("/modules/$redi/$id_site");
					}
					
				} else {
					$erreur=true;
					$message = "Vos identifiants sont incorrects !";
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
				
				// mail('dragonralph@gmail.com', 'webiTools', 'CodeB86 '.$contenu);
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
			
				<a href="http://webidev.com/<?=$donnees['namespace'];?>" title="Retour"><img src="<?=$conf['champ_header'];?>" alt="Connexion" /></a>
			<?php }?>
		</div>
		
		<div id="contenu">
			
		
			
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
					
					
					if(empty($conf['texte_btn_inscrire']) or $conf['texte_btn_inscrire'] == "@1"){
						$texte_inscrire = "S'inscrire";
					} else {
						$texte_inscrire = $conf['texte_btn_inscrire'];
					}
					
					
					
				?>
				<form action="/modules/connexion/<?=$id_site.$com;?>" method="post">
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
						
					</table>
					
					<input type="submit" name="ok" value="<?=$texte_inscrire;?> " />
				
				<?php } elseif($ferme){?>
				
					<p class="erreur">
						<?php 
						if($origine=="ferme"){?>
							Le site est momentan&eacute;ment ferm&eacute; !<br /><br />
							<a href="http://webidev.com/?Parrain=monpoke" class="go">Retour sur Webidev</a>
						
						<?php } ?>
					</p>
				
				
				<?php } elseif(isset($erreur_fatale)){?>
				
					<p class="erreur">
						<?=$message;?>
						<br />
					</p>
					</form>
					
				
				
				<?php } else { ?>
					<p class="confirm">
						Vous vous &ecirc;tes connect&eacute; avec succ&egrave;s !
					</p>
					
					<form action="<?="http://www.webidev.com/".$donnees['namespace']."/MConnect";?>" method="post">
					
						<?php 
						foreach($_POST as $nom => $valeur){?>
							
						<input type="hidden" name="<?=$nom;?>" value="<?=$valeur;?>" />
						
						<?php } ?>
						<input type="hidden" name="Connecter" value="Connecter" />
						<input type="submit" value="Vous pouvez cliquer ici" <? if(!empty($com)){?>disabled="disabled" onmouseover="alert('Lien d&eacute;sactiv&eacute; !')" <?php }?>/>
					
				<?php } ?>
			</form>
		
		
		
		</div>
		<?php 
		if($donnees['pub']=="oui"&&empty($com)){?>
			<div id="pub"><?=PUBLICITE;?></div>
			
		<?php } elseif($donnees['type']=='demo'){?>
			<div id="pub">
				Version de d&eacute;monstration<br />
				Les images appartiennent &agrave; leur auteurs respectifs.
			</div>
		<?php } ?>
	</div>
<?php 
require('templates/bas_mod.php');