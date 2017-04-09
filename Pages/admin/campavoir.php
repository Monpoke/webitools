<?php 

if(!is_admin()){
	Redirect("/membre/connexion");
}

$id=intval(getParam(1));

$retour=Query("SELECT * FROM publicites WHERE id='$id'");

if(mysql_num_rows($retour)!=1)
	Redirect("/admin/campagnes");

$donnees =mysql_fetch_array($retour);


$titre = "Panel";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Panel de gestion</h3>
	   <br /><br />
	   
		<h2><?=$donnees['nom'];?></h2>
		
		<img src="<?=$donnees['image'];?>" alt="Image pub" style="float: left; margin-right: 5px; max-width: 150px; max-height: 150px;" />
		Cette campagne renvoie vers l'url <a href="<?=$donnees['url'];?>" target="_blank"><?=$donnees['url'];?></a>.<br /><br />
		
		Voici le texte d'accroche :<br />
		
		<?=$donnees['texte'];?><br /><br />
		
		<i>Campagne active du <?=converseDate($donnees['debut_campagne'], 10);?> au <?=converseDate($donnees['fin_campagne'], 10);?></i>.<br />
		
		<br />
		<?=$donnees['out'];?> outs.
		
		
		<br /><br />
		
		<a href="/admin/campagnes">Retour</a>
		
   </div>
<?php 
require_once('templates/bas.php');


