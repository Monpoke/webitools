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


if($donnees['service_inscription'] =='non'){
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
if(getParam(2)=="preview" && ($donnees['id_membre'] ==get('id') or is_modo())){
	$com = "/preview";
}
elseif(!empty($pseudo_parrain2)){
	$com = "/$pseudo_parrain2";
}

if($conf['type']==1){
	Redirect("/modules/inscription/{$id_site}{$com}");
}


// VERIF SITES FERMES OU INSCRIPTIONS FERMES
$ferme = false;



/* CACHE DU SYSTEME */
$nom_en_cache = $id_site;
$extension_cache  = "cac";
$suffixe_cache  = "inv2";


$cache=new Cache();

if($cache->setPage($nom_en_cache, 'private', $extension_cache, $suffixe_cache)){

	if($cache->checkCache()){
		
			$retour = $cache->getCache('private');
			
			
			
	} else {
		
		$retour = @file_get_contents('http://www.webidev.com/'.$donnees['namespace']."/ViewPage?Id=".$conf['champ_31']);
		
		$cache->updateCache($retour);
		
		if(is_developper())
			echo "<b>Le fichier cache n'existait pas. Cr&eacute;ation effectu&eacute;e<br /></b>";
		
	}



}
extract($conf);
// on configure les textes ici
if(empty($conf['texte_pseudo'] ) or $conf['texte_pseudo']  == "@1"){
	$texte_pseudo = "Pseudo";
} else {
	$texte_pseudo = htmlentities($conf['texte_pseudo'] );
}

if(empty($conf['texte_pass1'] ) or $conf['texte_pass1']  == "@1"){
	$texte_pass1 = "Mot de passe";
} else {
	$texte_pass1 = $conf['texte_pass1'] ;
}

if(empty($conf['texte_pass2'] ) or $conf['texte_pass2']  == "@1"){
	$texte_pass2 = "Confirmer votre mot de passe";
} else {
	$texte_pass2 = $conf['texte_pass2'] ;
}

if(empty($conf['texte_email'] ) or $conf['texte_email']  == "@1"){
	$texte_email = "Email";
} else {
	$texte_email = $conf['texte_email'] ;
}

if(empty($conf['texte_date'] ) or $conf['texte_date']  == "@1"){
	$texte_date = "Date de naissance (jj-mm-aaaa)";
} else {
	$texte_date = $conf['texte_date'] ;
}

if(empty($conf['texte_sexe'] ) or $conf['texte_sexe']  == "@1"){
	$texte_sexe = "Sexe";
} else {
	$texte_sexe = $conf['texte_sexe'] ;
}

if(empty($conf['texte_btn_inscrire'] ) or $conf['texte_btn_inscrire']  == "@1"){
	$texte_inscrire = "S'inscrire";
} else {
	$texte_inscrire = $conf['texte_btn_inscrire'] ;
}


$style  = <<<EOF

.perso {
			
		color: #{$champ_29}; /* 10 */
		border-radius: {$champ_10}px; /* 10 */
		height: {$champ_28}px; /* 10 (bis) */
		background: #{$champ_14}; /* 14 */
		border: solid 1px black; /* 12 */
		text-align: center; 
	}
	
	.perso:focus {
		
		background: #{$champ_25} ; /* 14 (2) */
	}
	
	.submit  {
		border-radius: {$champ_15}px; /* 15  */
		
		width: {$champ_16}%; /* 16 */
		
		margin-top: 25px;/* 17 (bis) */
		
		background-color: #{$champ_32};
		
		color: #{$champ_33};
		
		
		height: {$champ_18}px; /* 18 */
		vertical-align: middle; 
		text-align: center;
	}
	
	.submit:hover  {

		background-color: #{$champ_34};
		
		color: #{$champ_35};
		
	}
	
	.td {
			text-align: $champ_19;?>; /* 19 */
		}
		
		
		.erreur {
		
			color: #{$champ_20}; /* 20 */
			font-weight: bold;
			
			text-align: center;
		}
		
		.confirm {
			color: #{$champ_26}; /* 20 (bis) */
			font-weight: bold;
			text-align: center;
			
		}
		
		.go {
			text-decoration: none;
			color: #{$champ_21}; /* 21 */
			font-style: italic;
			
		}
		
		.go:hover {
			text-decoration: underline;
			color: #{$champ_27}; /* 21 (bis) */
			font-style: italic;
			
		}
		
		
		#pub {
			position: fixed;
			bottom:0;
			left: 0;
			padding: 5px;
			-webkit-border-top-right-radius: 5px;
			-moz-border-radius-topright: 5px;
			border-top-right-radius: 5px;
			background: #ffffff;
			color: #000000;
		
		}
</style>
EOF;


$retour_ferme = @file_get_contents('http://www.webidev.com/'.urlencode($donnees['namespace']).'/MInscription');
if(!preg_match('#form#', $retour_ferme))
	$ferme=true;


if(isset($_POST['ok']) && !$ferme){

	$erreur = false;
	$message = "";
	
	// exit('verif!');
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
			if(preg_match("#Merci d'en choisir un autre.#", $result['content'])){
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
					$erreur=true;
					$message = "Une erreur est survenue ! <b>Code A56</b>";
				}
			 
			} else {
				$erreur=true;
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




ob_start();


echo '<form action="http://webis.plumedor.fr/modules/inscriptionv2/'.$id_site.$com.'" method="post">';
		
	$parrain="";
	
	if(is_developper() && !empty($pseudo_parrain) && $pseudo_parrain != "preview"){
		
		$pseudo_parrain = dechiffre($pseudo_parrain);
		
		$parrain = "Pseudo du parrain :<i> <a href='#csexe' style='cursor: default'>$pseudo_parrain</a></i><br />";
	
	}
		
		
	if(isset($erreur)&&$erreur)
		echo "<p class='erreur'>$message</p>";
		
	if(!isset($ok) or !$ok){
		
		$val_p = input_value('', 'Pseudo');
		$val_e = input_value('', 'Email');
		$val_a1 = input_value('', 'JourNez');
		$val_a2 = input_value('', 'MoisNez');
		$val_a3 = input_value('', 'AnneeNez');
		
		
		
echo <<<EOF
		
		<table border="0" style="width: 400px;">
		 
		  <tr>
		    <td style="width: 220px;" class="td"><label for="cpseudo">$texte_pseudo :</label></td>
		    <td><input class="perso" type="text" id="cpseudo" name="Pseudo" size="18" maxlength="30" value="$val_p" /></td>
		  </tr>
		  <tr>
		    <td class="td"><label for="cpass1">$texte_pass1 :</label></td>
		    <td><input type="password" class="perso" id="cpass1" name="Password" size="18" maxlength="16" value="" /></td>
		  </tr>
		  <tr>
		    <td class="td"><label for="cpass2">$texte_pass2 :</label></td>
		    <td><input type="password" class="perso" id="cpass2" name="Password2" size="18" maxlength="16" value="" /></td>
		  </tr>
		  <tr>
		    <td class="td"><label for="cemail">$texte_email :</label></td>
		    <td><input type="text" class="perso" id="cemail" name="Email" size="18" maxlength="50" value="$val_e" /></td>
		  </tr>
		  
		  <tr>
		    <td class="td">$texte_date :</td>
		    <td>
			<input type="text" class="perso" name="JourNez" size="2" maxlength="2" value="$val_a1" /> -
			<input type="text" class="perso" name="MoisNez" size="2" maxlength="2" value="$val_a2" /> -
			<input type="text" class="perso" name="AnneeNez" size="4" maxlength="4" value="$val_a3" /> 
			</td>
		  </tr>	

		  <tr>
		    <td class="td"><label for="csexe">$texte_sexe :</label></td>
		    <td><select name="Sexe" class="perso" id="csexe"><option value="" selected="selected">-------------------</option><option value="M" >Masculin</option><option value="F" >F&eacute;minin</option></select></td>
		  </tr>			  
		  
		  <tr>
			
		    <td colspan="2"><center>$parrain<input type="submit" class="submit" name="MembreInscription" value="$texte_inscrire" /></center></td>
		  </tr>
		<input type="hidden" name="ok" value="true" />
		</table>
		</form>
EOF;
}
else {



$truc="";
if(!empty($conf['champ_30']))
	$truc="/ViewPage?Id=".$conf['champ_30'];

	
	
$namespace=$donnees['namespace'].$truc;


if($conf['champ_36']=="1")
	Redirect("http://webidev.com/$namespace");



echo <<<EOF
<p class="confirm">
	$message_ok<br /><br />
						
<a href="http://webidev.com/$namespace" class="go">Cliquez ici !</a>
</p>
EOF;



}


$form=ob_get_clean();


$retour = preg_replace("#<form (.+)(MInscription)?<b>Inscription</b>(.+)</form>#Usi", $form, $retour);
$retour = preg_replace("#\[WT:inscription\]#", $form, $retour);


$pub = <<<EOF
_gaq.push(['_setAccount', 'UA-34275806-2']);
 _gaq.push(['_setDomainName', 'plumedor.fr']);
EOF;

$compa = <<<EOG
/prototype.js"></script>

<script type="text/javascript" src="/js/jquery-1.6.1.min.js"></script>

<script type="text/javascript">
 var \$j = jQuery.noConflict();
 
 
</script>

EOG;



$retour = preg_replace("#_gaq.push(['_setAccount', 'UA\-22532783\-1']);#", $pub, $retour);

$retour = preg_replace('#/prototype.js"></script>#Usi', $compa, $retour);
$retour = preg_replace('#</style>#Usi', $style, $retour);

// supprime pub
// $retour = preg_replace('#<script type="text/javascript">(.+)var advst_zoneid = 31023;(.+)</script>#Usi', "", $retour);
// $retour = preg_replace('#<script type="text/javascript" src="http://ad.advertstream.com/advst_p.php"></script>(.+)<noscript>(.+)</noscript>#Usi', "", $retour);

echo $retour;

if($donnees['pub']=="oui" && PUB_PLUME){?>
	<div id="pub"><a href="http://plumedor.fr">Pub : <b>Plume d'Or</b> ouvre en <b>b&ecirc;ta-test</b></a></div>
	
<?php }elseif($donnees['pub']=="oui"){?>
	<div id="pub"><?=PUBLICITE;?></div>
	
<?php }elseif($donnees['type']=='demo'){?>
	<div id="pub">
		Version de d&eacute;monstration<br />
		Les images appartiennent &agrave; leur auteurs respectifs.
	</div>
<?php } ?>
</div>

<script type="text/javascript">

	$j(function(){
	
		var couleur = $j(".trcorpspage").css('color');
		var bgimg = $j(".trcorpspage").css('background-image');
		var bgcolor = $j(".trcorpspage").css('background-color');
		
		
		
		$j("#pub").css('color', couleur);
		$j("#pub").css('background-color', bgcolor);
		$j("#pub").css('background-image', bgimg);
		
		
		
	
	
	});

</script>

<?php
$retour = preg_replace("#</style>#Usi", $style, $retour);



