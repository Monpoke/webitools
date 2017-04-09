<?php 

$id_site = intval(getParam(1));
$retour_site = Query("SELECT * FROM sites WHERE id_site='$id_site' and id_membre='".get('id')."' and etat='1'");

if(mysql_num_rows($retour_site)!=1)
	Redirect("/panel");

	
$donnees=mysql_fetch_array($retour_site);


if(isset($_SESSION['validation_'.$id_site]) && getParam(2)=="check"){

	$retour = @file_get_contents('http://webidev.com/'.urlencode($donnees['namespace']));
	
	if(preg_match("#".$_SESSION['validation_'.$id_site]."#", $retour)){
		
		Query("UPDATE sites SET etat='2' WHERE id_site='$id_site'");
		Redirect("/panel");
	
	} else {
		$erreur = true;
		$message = "La balise est introuvable";
	}	

}

$titre = "Valider un site";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Valider un site</h3>
	   
		<?php 
		if(isset($erreur)&&$erreur){?>
			<p class="erreur">
				<?=$message;?>
			</p>
		<?php } ?>
		
		<p>
			Veuillez mettre ce texte sur votre page d'accueil et v&eacute;rifiez qu'il soit accessible aux visiteurs : <b>
			<?php 
			if(!isset($_SESSION['validation_'.$id_site]))
				$_SESSION['validation_'.$id_site] = "tools-".genSecuriteId(4);
				
			echo $_SESSION['validation_'.$id_site];
			?></b>
			<br /><br />
			
			Une fois plac&eacute;, <a href="/website/validerv/<?=$id_site;?>/check">cliquez-ici</a>. Une fois la v&eacute;rification effectu&eacute;e, vous pourrez retirer ce marqueur.
		</p>
		
		
   </div>
<?php 
require_once('templates/bas.php');


