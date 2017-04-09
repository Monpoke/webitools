<?php 


$conf=mysql_fetch_array(Query("SELECT * FROM tchat WHERE id_site='$id_site'"));


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
	
	
	if(empty($_POST['id_page']) or is_nan($_POST['id_page'])){
		$erreur=true;
		$message.="Verifiez l'id de page<br />";
	} else
		$id_page=intval($_POST['id_page']);
		
		
	if(empty($_POST['champ_1']) or strtolower($_POST['champ_1']) != "asc" && strtolower($_POST['champ_1']) != "desc"){
		$erreur=true;
		$message.="Mettez ASC ou DESC<br />";
	} else
		$ordre=secure($_POST['champ_1']);
		
	
	
	if(!$erreur){
		
		
		Query("UPDATE tchat SET champ_1='$ordre', id_page='$id_page', date_modif='".time()."' WHERE id_site='$id_site'");
		Redirect("/mods/perso/tchat/$id_site");
	
	}
	

}


?>

<form action="/mods/perso/tchat/<?=$id_site?>" method="post">
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
	
	
	
	<br /><br />
	<h2>Id de la page o&ucirc; greffer le tchat</h2>
	<input type="text" name="id_page" value="<?=input_value($conf['id_page'], 'id_page');?>" /><br />
	<i>Placez la balise [WT:Tchat] sur cette page</i>
	<br /><br />
	
	<h2>Affichage des message</h2>
	<input type="text" name="champ_1" value="<?=input_value($conf['champ_1'], 'champ_1');?>" /><br />
	<i>ASC : affichage croissant | DESC : affichage d&eacute;croissant</i>
	
	<br /><br />
	
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
	&lt;a <?=HTTP_PUBLIC;?>modules/tchat/<?=$id_site;?>&gt;Lien public du tchat&lt;/a&gt;
</code><br /><br />
Placer ce code sur la page ou se situe le tchat<br /><br />
<code>
	[IF Modo = 1][WT:AdminTchat][/IF]
	[IF Modo != 1][WT:Tchat][/IF]
</code><br /><br />



