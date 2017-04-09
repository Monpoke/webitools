<?php 


$conf=mysql_fetch_array(Query("SELECT * FROM liensjours WHERE id_site='$id_site'"));


if(isset($_POST['charge_exemple'])){
	
	if(!empty($_POST['exemple']) && preg_match("#^site_([0-9])$#", $_POST['exemple'])){
	
		$id_site2 = intval(substr($_POST['exemple'], strlen('site_')));
		
		$retour = Query("SELECT * FROM sites WHERE service_liensjours='oui' && id_site='$id_site2'");
		
		if(mysql_num_rows($retour)==1){
			
			$donnees = mysql_fetch_array($retour);
			$retour = Query("SELECT * FROM liensjours WHERE id_site='{$donnees['id_site']}'");
		
			$champs = array();
			$valeurs = array();
			
			$donnees_exemple = mysql_fetch_array($retour);
			
			$prendre=false;
			foreach($donnees_exemple as $nom => $val){
				if($prendre){
					$champs[] = $nom;
					$valeurs[] = Secure($val);
				}
				
				if($prendre)
					$prendre=false;	
				else
					$prendre=true;
			}
			
			$valeurs[0] = $id_site;
			
			
			// sql
			$sql = "INSERT INTO liensjours(";
			
			foreach($champs as $nom){
				$sql.= $nom.", ";
			}
			
			$sql=substr($sql,0,-2);
			
			$sql.=") VALUES (";
			
			foreach($valeurs as $nom){
				$sql.= "'$nom', ";
			}
			
			$sql=substr($sql,0,-2);
			
			$sql .= ")";
			// on supprime les liens jours en cours
			Query("DELETE FROM liensjours WHERE id_site='$id_site'");
			Query($sql);
			
			Redirect("/mods/perso/liensjours/$id_site/modok");
		}
		
	}


}

elseif(isset($_POST['formu'])){

	$erreur = false;
	$message="";
	
	

	// VERIFS CSS
	$sql="";
	$url = array('1', '2', '3', '4', '5', '6', '7');
	
	$i=1;
	foreach($_POST as $nom => $valeur){
	
		if(preg_match("#lien_#", $nom)){
		
			$id=explode('_', $nom);
			$id=$id[1];
			
			
			if(in_array($id, $url)){
			
				if(!empty($valeur) && !isUrl($valeur)){
					$erreur = true;
					$message .= "Le lien $i est incorrect !"."<br />";
				
				}
			
			} else {

				exit("$nom a un probleme !");
			}			
		
		
			$i++;
		}
	
	}
	
	
	if(!$erreur){
	
		$sql="";
		
		foreach($_POST as $nom => $valeur){
		
			if(preg_match("#lien_#", $nom)){
			
				$nom = substr($nom, 5);

				//on associe
				
				$sql.="lien_$nom='".Secure($valeur)."', ";
			}
		
		
		}
	
		
		
		Query("UPDATE liensjours SET $sql date_modif='".time()."' WHERE id_site='$id_site'");
		Redirect("/mods/perso/liensjours/$id_site");
	
	}
	

}


?>

<form action="/mods/perso/liensjours/<?=$id_site?>" method="post">
	<?php 
	if(isset($erreur)&&$erreur){?>
		<p class="erreur">
			<?=$message;?>
		</p>
	<?php } elseif(getParam(3)=="modok"){?>
		<p class="confirm">
			Le mod&egrave;le a &eacute;t&eacute; charg&eacute; avec succ&egrave;s !
		</p>
	<?php } ?>
	
	
	Vous pouvez ins&eacute;rer jusque 7 liens, qui changeront tout les jours &agrave; minuit. Les liens que vous inserez peuvent &ecirc;tre des vid&eacute;os, des playlists musicales, des images... <br />
	<i><b>Les liens mon&eacute;tis&eacute;s et hors r&egrave;glement sont interdits et seront sanctionn&eacute;s !</b></i>
	<br /><br />
	<h2>Lien 1</h2>
	<input type="text" name="lien_1" value="<?=input_value($conf['lien_1'], 'lien_1');?>" /><br /><br />
	
	<h2>Lien 2</h2>
	<input type="text" name="lien_2" value="<?=input_value($conf['lien_2'], 'lien_2');?>" /><br /><br />
	
	<h2>Lien 3</h2>
	<input type="text" name="lien_3" value="<?=input_value($conf['lien_3'], 'lien_3');?>" /><br /><br />
	
	<h2>Lien 4</h2>
	<input type="text" name="lien_4" value="<?=input_value($conf['lien_4'], 'lien_4');?>" /><br /><br />
	
	<h2>Lien 5</h2>
	<input type="text" name="lien_5" value="<?=input_value($conf['lien_5'], 'lien_5');?>" /><br /><br />
	
	<h2>Lien 6</h2>
	<input type="text" name="lien_6" value="<?=input_value($conf['lien_6'], 'lien_6');?>" /><br /><br />
	
	<h2>Lien 7</h2>
	<input type="text" name="lien_7" value="<?=input_value($conf['lien_7'], 'lien_7');?>" /><br /><br />
	
	<input type="submit" value="Sauvegarder" />
	<input type="hidden" name="formu" value="ok" />
</form>

<br /><br /><br />
<?php /*
<b><u>Charger un site exemple</u></b><br />
Exemple &agrave; charger :

<form action="/mods/perso/liensjours/<?=$id_site?>" method="post">
	<select name="exemple">
		<option value="">--------------</option>
		<?php 
		$retour=Query("SELECT * FROM sites WHERE type='demo' and service_liensjours='oui'");
		
		while($do=mysql_fetch_array($retour)){
		?>
			<option value="site_<?=$do['id_site'];?>"><?=$do['nom'];?></option>
		
		<?php
		}?>
	</select>
	<br />
	<input type="hidden" name="charge_exemple" value="true" />
	<input type="submit" value="Charger" onclick="return(confirm('Cela &eacute;crasera toutes vos donn&eacute;es !')); return false;" />
</form>
*/?>


<b><u>Rappel :</u></b><br />
<br />
<code>
	&lt;a <?=HTTP_PUBLIC;?>modules/liensjours/<?=$id_site;?>&gt;Acc&eacute;dez ici au contenu du jour&lt;/a&gt;
</code><br /><br />



