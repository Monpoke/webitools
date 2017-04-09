<?php 

$id_site = intval(getParam(1));
$retour_site = Query("SELECT * FROM sites WHERE id_site='$id_site' and id_membre='".get('id')."' and etat='1'");

if(mysql_num_rows($retour_site)!=1)
	Redirect("/panel");

	
$donnees=mysql_fetch_array($retour_site);


if(isset($_POST['valider'])){
	$erreur=false;
	$message = "";
	


	if(empty($_POST['pass']) or strlen($_POST['pass']) < 4){
	
		$erreur = true;
		$message .= "Le mot de passe est incorrect !"."<br />";
	
	}

		
	if($erreur==false){
		

		// Submit those variables to the server
		$post_data = array(
			'nomsite' => $donnees['namespace'],
			'password' => $_POST['pass'],
		);
		 
		// Send a request to example.com 
		$result = post_request('http://www.webidev.com/fr/WebiConnect', $post_data);
		 
		if ($result['status'] == 'ok'){
		 
			// print the result of the whole request:
			if(preg_match("#http://www\.webidev.com/Images/Decors/Warning\.png#", $result['content'])){
				$erreur = true;
				$message = "La validation a &eacute;chou&eacute;e !";
			} else {
				Query("UPDATE sites SET etat='2' WHERE id_site='$id_site'");
				Redirect("/panel");
			}
		 
		}
		else {
			Redirect("/website/validerw/$id_site", 5);
			exit('Une erreur est survenue.<br />Veuillez reessayez :/');
		}
				
	}


}

$titre = "Valider un site";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Valider un site</h3>
	   
		<p>
			Veuillez entrer le mot de passe que vous utilisez pour le compte <i><?=$donnees['namespace'];?></i>
			
		</p>
		
		<form action="/website/validerw/<?=$id_site;?>" method="post">
		
			<?php 
			if(isset($erreur)&&$erreur){?>
				<p class="erreur">
				
					<?=$message;?>
				</P>
			<?php } 
			?>
			<br />
			<label for="cpseudo">Pseudo :</label> <input type="text" name="pseudo" value="<?=$donnees['namespace'];?>" readonly="readonly" id="cpseudo" /><br />
			<label for="cpass">Mot de passe :</label> <input type="password" name="pass" id="cpass" /><br />
			
			<br />
			<b>Note importante :</b> Le mot de passe n'est pas enregistr&eacute; !
			<br />
			<br />

			<input type="submit" name="valider" value="Valider le site" />

		</form>
	   
   </div>
<?php 
require_once('templates/bas.php');


