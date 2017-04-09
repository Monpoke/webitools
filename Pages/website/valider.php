<?php 

$id_site = intval(getParam(1));
$retour_site = Query("SELECT * FROM sites WHERE id_site='$id_site' and id_membre='".get('id')."' and etat='1'");

if(mysql_num_rows($retour_site)!=1)
	Redirect("/panel");

	
$donnees=mysql_fetch_array($retour_site);

$titre = "Valider un site";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Valider un site</h3>
	   
		<p>
			Pour valider <b><?=$donnees['nom'];?></b> et commencer &agrave; profiter des modules mis &agrave; votre disposition, vous disposez de deux moyens de validation :
			
			<br /><br />
			
			-> <a href="/website/validerv/<?=$id_site;?>">Cliquez-ici pour placer une balise sur votre site <i>(recommand&eacute;)</i></a><br />
			-> <a href="/website/validerw/<?=$id_site;?>">Cliquez-ici pour vous connecter avec vos identifiants Webidev <i>(Plus rapide)</i></a>
		</p>
	   
   </div>
<?php 
require_once('templates/bas.php');


