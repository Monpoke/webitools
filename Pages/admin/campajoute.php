<?php 

if(!is_admin()){
	Redirect("/membre/connexion");
}


if(isset($_POST['save'])){

	$erreur=false;
	$message="";
	
	if(empty($_POST['nom']) or strlen($_POST['nom']) > 20){
		$message .= "Le nom doit comporter 1 &agrave; 20 caract&egrave;res !"."<br />";
		$erreur=true;
	}
	else
		$nom = Secure($_POST['nom']);
		
	if(!isUrl($_POST['image'])){
		$message .= "L'url image est incorrect !"."<br />";
		$erreur=true;
	}
	else
		$image = Secure($_POST['image']);
	
	if(!isUrl($_POST['url'])){
		$message .= "L'url est incorrect !"."<br />";
		$erreur=true;
	}
	else
		$url = Secure($_POST['url']);
		
	if(empty($_POST['texte']) or strlen($_POST['texte']) > 40){
		$message .= "Le nom doit comporter 1 &agrave; 40 caract&egrave;res !"."<br />";
		$erreur=true;
	}
	else
		$texte = Secure($_POST['texte']);
		
	if(empty($_POST['debut']) or !converseTime($_POST['debut'])){
		$message .= "La date d&eacute;but est incorrecte !"."<br />";
		$erreur=true;
	}
	else
		$debut = converseTime($_POST['debut']);
		
	
	if(empty($_POST['fin']) or !converseTime($_POST['fin'])){
		$message .= "La date fin est incorrecte !"."<br />";
		$erreur=true;
	}
	else
		$fin = converseTime($_POST['fin']);
		
		
	if($erreur==false){
		
		
		Query("INSERT INTO publicites(nom, texte, url, date_ajout, debut_campagne, fin_campagne, image) VALUES('$nom', '$texte', '$url', '".time()."', '$debut', '$fin', '$image')");
		Redirect("/admin/campagnes");
	
	
	
	}
	




}

$titre = "Panel";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Panel de gestion</h3>
	   
		<form action="/admin/campajoute" method="post">
			
			<p>
				Ajoutez ici une campagne qui apparaitra sur le site.<br /><br />
			</p>
			
			<?php 
			if(isset($erreur)&&$erreur){?>
			<p class="erreur">
				<?=$message;?><br />
			</p>
			
			<?php } ?>
			
			<label for="nom">Nom :</label> <input type="text" name="nom" id="nom" value="<?=input_value('', 'nom');?>" /><br /><br />
			
			<label for="image">Image :</label> <input type="text" name="image" id="image" value="<?=input_value('', 'image');?>" /><br /><br />
			
			<label for="url">URL :</label> <input type="text" name="url" id="url" value="<?=input_value('', 'url');?>" /><br /><br />
			
			<label for="texte">Texte :</label> <input type="text" name="texte" id="texte" value="<?=input_value('', 'texte');?>" /><br /><br />
			
			<label for="debut">D&eacute;but :</label> <input type="text" name="debut" id="debut" size="10" maxlength="10" value="<?=input_value('', 'debut');?>" /> - <label for="fin">Fin :</label> <input type="text" name="fin" id="fin" size="10" maxlength="10" value="<?=input_value('', 'fin');?>" /><br />
			<small><i>Forme : JJ/MM/AAAA</i></small>
			<br /><br />
			
			<input type="submit" name="save" value="Sauver" /> <a href="/admin/campagnes">Retour</a>
			
		
		</form>
		
   </div>
<?php 
require_once('templates/bas.php');


