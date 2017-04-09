<?php 

if(!is_membre())
	Redirect("/");

	
if(!empty($_POST['modif'])){

	$erreur = false;
	$message = "";
	
	if(!empty($_POST['pass']) and strlen($_POST['pass'])<6){
		$erreur=true;
		$message = "Le mot de passe doit comporter 6 caract&egrave;res minimum !<br />";
	}
	
	elseif(empty($_POST['pass'])){
		$new_pass = get('pass');
		
	}
	else
		$new_pass = cryptage($_POST['pass']);
	
	
	
	
	if($erreur==false){
		Query("UPDATE membres SET pass='$new_pass' WHERE id='".get('id')."'");
		
		if($new_pass!=get('pass'))
			Redirect("/membre/deconnexion/securite");
	}

}
	
	
	
$titre = "Mon compte";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Mon compte</h3>
	   
		Modifiez ici les param&egrave;tres de votre compte.<br /><br />
		
		<form action="/compte" method="post">
		
			<label for="pass">Mot de passe :</label> <input type="password" name="pass" id="pass" /><br />
			<i>Laissez vide si vous ne souhaitez pas le changer</i><br /><br />
			
			<?php 
			if($_SESSION['donnees_membre']['vip']=='oui'){
			
				if($_SESSION['donnees_membre']['fin_vip']<time()){
					Query("UPDATE membres SET vip='non', fin_vip='0' WHERE id='{$_SESSION['donnees_membre']['id']}'");
				?>
					<b>Compte premium expir&eacute; depuis le <?=converseDate($_SESSION['donnees_membre']['fin_vip']);?></b><br />
				<?php } else { ?>
					<b>Compte premium actif jusqu'au <?=converseDate($_SESSION['donnees_membre']['fin_vip']);?></b><br />
			<?php }
			
			}
			?>
			
			
			<br />
			
			<input type="submit" name="modif" value="Modifier" />
		
		
		
		</form>
	   
   </div>
<?php 
require_once('templates/bas.php');


