<?php 

if(is_membre()){
	Redirect("/panel");
}

if(isset($_POST['inscription'])){
	
	$erreur=false;
	$message = "";
	
	if(empty($_POST['pseudo']) or strlen($_POST['pseudo']) < 4){
		$erreur = true;
		$message .= "Le pseudo doit comporter 4 caract&egrave;res minimum."."<br />";
	}
	
	elseif(in_array($_POST['pseudo'], $pseudos_reserves)){
		$erreur = true;
		$message .= "Le pseudo est r&eacute;serv&eacute;."."<br />";
	}
	
	else {
		$pseudo = Secure($_POST['pseudo']);
		$retour = Query("SELECT * FROM membres WHERE pseudo='$pseudo'");
		
		if(mysql_num_rows($retour)!=0){
			$erreur = true;
			$message .= "Le pseudo est d&eacute;j&agrave; utilis&eacute;."."<br />";
			
		}
	}
	
	if(empty($_POST['pass']) or strlen($_POST['pass']) < 6){
		$erreur = true;
		$message .= "Le mot de passe doit comporter 6 caract&egrave;res minimum."."<br />";
	}
	
	if(empty($_SESSION['captcha']) or empty($_POST['capt']) or $_POST['capt'] != $_SESSION['captcha']){
	
		$erreur = true;
		$message .= "Le captcha est invalide."."<br />";
	}
	
	if(!$erreur){
		$pass = cryptage($_POST['pass']);
		$date = time();
		
		$ip = $_SERVER['REMOTE_ADDR'];
		
		Query("INSERT INTO membres(pseudo, pass, date_inscription, rang, ip, reglement) VALUES('$pseudo', '$pass', '$date', '".MEMBRE."', '$ip', 'oui')");
		
		Redirect("/membre/connexion/inok");
		
	}

}


$titre = "Inscription";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Inscription au service</h3>
	   
		<form action="/membre/inscription" method="post">
		
			<?php 
			if(isset($erreur)&&$erreur){?>
				<p class="erreur">
				
					<?=$message;?>
				</P>
			<?php } ?>
			<br />
			<label for="cpseudo">Pseudo :</label> <input type="text" name="pseudo" id="cpseudo" /><br />
			<label for="cpass">Mot de passe :</label> <input type="password" name="pass" id="cpass" /><br />
			<label for="ccapt">Combien font 
			<?php 
			$operation = rand(1, 2); 
			if($operation==1){ 
				$op=rand(1, 5);
				$op2 = rand(6, 10);
				$_SESSION['captcha'] = $op2-$op;
				echo "<b>$op2 - $op</b>";
				
			}
			else { 
				$op=rand(1, 5);
				$op2 = rand(6, 10);
				$_SESSION['captcha'] = $op2+$op;
				echo "<b>$op2 + $op</b>";
				
			}
			
			
			?> ?</label> <input type="text" name="capt" id="ccapt" /><br />
			
			<br />
			
			En cliquant sur le bouton Inscription, vous acceptez les <a href="/cgu">CGU</a>.<br />

			<input type="submit" name="inscription" value="Inscription" />

		</form>
	   
   </div>
<?php 
require_once('templates/bas.php');


