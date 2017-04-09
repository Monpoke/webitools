<?php 


$com="";
$id=get('id');

if(is_modo()){
	
	$id_2 = intval(getParam(1));
	
	if(is_modo(true))
		$cond = "membres.rang < '".MODERATEUR."'";
	else
		$cond = "membres.rang<='".ADMINISTRATEUR."'";
		
	
	$retour=Query("SELECT * FROM membres WHERE id='$id_2' and $cond");
	
	if(mysql_num_rows($retour)==1){
		$com="/$id_2";
		$id=$id_2;
	
	}
	

}


if(isset($_POST['ajouter'])){
	$erreur=false;
	$message ="";
	
	if(empty($_POST['nom'])or strlen($_POST['nom'])<3 or strlen($_POST['nom'])>50){
		$erreur = true;
		$message .= "Le nom du site doit &ecirc;tre compris entre 3 et 50 caract&egrave;res"."<br />";
	}
	
	if(empty($_POST['namespace']) or strlen($_POST['namespace'])<3 or strlen($_POST['namespace'])>255){
		$erreur = true;
		$message .= "L'url est incorrect"."<br />";
	}
	
	elseif(!preg_match("#^([0-9a-z-]+)$#", $_POST['namespace'])){
		$erreur = true;
		$message .= "L'url du site ne doit &ecirc;tre compos&eacute; que de lettres minuscules (de 'a' &agrave; 'z') ou de chiffres (de 0 &agrave; 9). Le tiret '-' est autoris&eacute;"."<br />";
	}
	
	
	
	else {
		$namespace=urlencode(Secure($_POST['namespace']));
		$retour_site = @file_get_contents('http://webidev.com/'.$namespace);
		
		if(!preg_match("#virtuel avec Webidev#", $retour_site) && !preg_match("#Retour sur Webidev#", $retour_site) ){
			$erreur = true;
			$message .= "Le site est introuvable !"."<br />";
		
		} else {
			$retour = Query("SELECT * FROM sites WHERE namespace='$namespace' and etat!='1'");
			if(mysql_num_rows($retour)!=0){
				$erreur = true;
				$message .= "Le site a d&eacute;j&agrave; &eacute;t&eacute; valid&eacute; autre part.";
			
			}
		
		}
	}
	
	
	
	if($erreur==false){
	
		$nom = Secure($_POST['nom']);
		$namespace = Secure($_POST['namespace']);
		
		Query("INSERT INTO sites(nom, namespace, date_ajout, id_membre, etat) VALUES('$nom', '$namespace', '".time()."', '$id', '1')");
		
		if(empty($com))
			Redirect("/panel");
		else
			Redirect("/admin/membre_voir/$id");
	}
	
	

}





$titre = "Ajouter un site";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Ajouter un site</h3>
	   
		<form action="/website/ajouter<?=$com;?>" method="post">
		
			<?php 
			if(isset($erreur)&&$erreur){?>
				<p class="erreur">
					<?=$message;?>
				</P>
			<?php } ?>
			<br />
			
			<label for="cnom">Nom :</label> <input type="text" name="nom" id="cnom" /><br />
			<label for="cacces">Url d'acc&egrave;s : http://webidev.com/</label> <input type="text" name="namespace" id="cacces" /><br />
			
			<br />

			<input type="submit" name="ajouter" value="Ajouter" />
			<br />
			<i>Si un probl&egrave;me survient, rendez vous <a href="/forum">ICI</a></i>
		</form>
	   
   </div>
<?php 
require_once('templates/bas.php');


