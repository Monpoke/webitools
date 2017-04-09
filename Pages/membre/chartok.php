<?php 

if(!is_membre() or get('reglement') != "non"){
	Redirect("/");
}


Query("UPDATE membres SET reglement='oui' WHERE id='".get('id')."'");


Redirect("/panel", 2);

$titre = "Charte";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Acceptation de la charte</h3>
	   
		<h1 class="confirm"><br /><br />
			Merci de votre confiance !
		</h1>
	   
   </div>
<?php 
require_once('templates/bas.php');


