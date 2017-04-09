<?php 

$id_fichier = intval(Param(1))-1;

$templates = fopen("./fichiers/temails.txt", "r");
					
$ligne = fgets($templates);
					
// on verifie le nom du fichier
if(empty($ligne))
	Redirect("/temails");

	
$noms = explode(';', $ligne);

if(isset($noms[$id_fichier]))
	$nom = $noms[$id_fichier];
else {
	Redirect("/temails");

}
// bon le fichier existe, on va aller voir sur le ftp :)

// on ouvre la co ftp
$connect = @ftp_connect('127.0.0.1');

if (@ftp_login($connect, FTP_USER, FTP_PASSWORD)) {

	// aller dans le dossier 
	$directory="/httpdocs/templates/mails/";
	$dossier=ftp_chdir ($connect,$directory);

	// verif si le fichier existe deja
	$fichier_temporaire = $_SESSION['fichier_temp'] = $_SERVER['DOCUMENT_ROOT']."/temp/".chaine_unique2(20);

	$local = fopen($fichier_temporaire, 'w');

	if(!ftp_fget($connect, $local, $directory.$nom.".php", FTP_ASCII, 0)) {
		exit("Une erreur est survenue :o");
	}

	// fermeture
	fclose($local);
	ftp_close($connect);

} else {
	exit("Une erreur est survenue :/");

}
	
	

	
if(isset($_POST['formulaire'])){

	$template = Secure($_POST['nom']).".php";
	
	// on ouvre la co ftp
	$connect = @ftp_connect('127.0.0.1');
	
	if (ftp_login($connect, FTP_USER, FTP_PASSWORD)) {
		
		// aller dans le dossier 
		$directory="/httpdocs/templates/mails";
		$dossier=ftp_chdir ($connect,$directory);

		// verif si le fichier existe deja
	
		$fichier_temporaire = $_SERVER['DOCUMENT_ROOT']."/temp/".chaine_unique2(20);
	
		// création d'un fichier temporaire
		$temp = fopen($fichier_temporaire, "x+");
		
		fputs($temp, $_POST['syntaxe']);
		// fermeture
		fclose($temp);
		
		if (ftp_put($connect, $directory."/".$template, $fichier_temporaire, FTP_ASCII)) {
			
			$confirm = true;
			
			ftp_quit($connect);
			unlink($fichier_temporaire);
			
			Redirect("/temails/editer/".($id_fichier+1)."/ok");
		} else {
			$erreur = true;
			$message = "Une erreur d'enregistremenet a eue lieue";
		}

		
		
		
		
		
		ftp_quit($connect);
		unlink($fichier_temporaire);
	
		
	} else {
		$erreur = true;
		$message = "L'enregistrement a &eacute;chou&eacute;";
	}

	


}


ob_start();
	require_once($fichier_temporaire);
$contenu = ob_get_clean();


require_once("templates/haut.php");
?>
<!-- start content-outer -->
<div id="content-outer">
<!-- start content -->
<div id="content">


<div id="page-heading"><h1>&Eacute;diter un template</h1></div>


<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
<tr>
	<th rowspan="3" class="sized"><img src="/images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
	<th class="topleft"></th>
	<td id="tbl-border-top">&nbsp;</td>
	<th class="topright"></th>
	<th rowspan="3" class="sized"><img src="/images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
</tr>
<tr>
	<td id="tbl-border-left"></td>
	<td>
	<!--  start content-table-inner -->
	<div id="content-table-inner">
	
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
	<td>
	
	
	<form action="/temails/editer/<?=$id_fichier+1;?>" method="post">
		
			
			<?php 
			
			if(isset($erreur) && $erreur){?>
				<!--  start message-red -->
				<div id="message-red">
					<table border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="red-left">
								Des erreurs sont survenues : veuillez v&eacute;rifier le formulaire.
								
								<?php if(isset($message)){?>
									<br /><br />
									<?=$message;?>
															
								<?php } ?>
							</td>
							<td class="red-right"><a class="close-red"><img src="/images/table/icon_close_red.gif"   alt="" /></a></td>
						</tr>
					</table>
				</div>
				<br /><br />
				<!--  end message-red -->
			<?php } 
			elseif(Param(2)=="ok"){?>
				<!--  start message-green -->
				<div id="message-green">
					<table border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="green-left">
								Les modifications ont &eacute;t&eacute; sauvegard&eacute;es.
							</td>
							<td class="green-right"><a class="close-green"><img src="/images/table/icon_close_green.gif"   alt="" /></a></td>
						</tr>
					</table>
				</div>
				<br /><br />
				<!--  end message-red -->
			<?php }  
			?>
		
			<!-- start id-form -->
			<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
				
				<tr>
					<th valign="top"><label for="nom">Nom du template :</label></th>
					<td>
						<input type="text" class="inp-form<?=(!empty($err_nom)) ? "-error" : "";?>" readonly="true" value="<?=input_value($nom, 'nom');?>" name="nom" id="nom"/>.php
					</td>
					<td>
						<?=(!empty($err_nom)) ? '<div class="error-left"></div><div class="error-inner">'.$err_nom.'</div>' : "";?>
					
					</td>
				</tr>
			
				
				<tr>
					<th valign="top"><label for="syntaxe">Template :</label></th>
					<td>
						<textarea name="syntaxe" id="syntaxe" rows="15" cols="50" ><?=input_value($contenu, 'syntaxe');?></textarea><br />
						<small>Utilisez les variables avec <i>{NOM}</i></small>
					</td>
					<td>
						<?=(!empty($err_syntaxe)) ? '<div class="error-left"></div><div class="error-inner">'.$err_syntaxe.'</div>' : "";?>
					
					</td>
				</tr>
				
				
				
				
				<tr>
					<th>&nbsp;</th>
					<td valign="top"><br /><br />
						<input type="submit" value="" class="form-submit" />
						<input type="reset" value="" class="form-reset"  />
					</td>
					<td></td>
				</tr>
				
			</table>

			<input type="hidden" name="formulaire" value="true" />
		</form>
		
		<br /><br />
	</td>
	<td>

	
	
	
	
	
	
	
	
	
	
	
	
	
	<!--  start related-activities -->
	<div id="related-activities">
		
		<!--  start related-act-top -->
		<div id="related-act-top">
		<img src="/images/forms/header_related_act.gif" width="271" height="43" alt="" />
		</div>
		<!-- end related-act-top -->
		
		<!--  start related-act-bottom -->
		<div id="related-act-bottom">
		
			<!--  start related-act-inner -->
			<div id="related-act-inner">
			
				<div class="left"><a href=""><img src="/images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
				<div class="right">
					<h5>Les templates</h5>
					
					<ul class="greyarrow">
						<li><a href="/temails">Accueil du module</a></li> 
					</ul>
				</div>
				
				<div class="clear"></div>
				
				
			</div>
			<!-- end related-act-inner -->
			<div class="clear"></div>
		
		</div>
		<!-- end related-act-bottom -->
	
	</div>
	<!-- end related-activities -->

</td>
</tr>
	
	
	
	<tr>
		<td><img src="/images/shared/blank.gif" width="695" height="1" alt="blank" /></td>
		<td></td>
	</tr>
</table>
 
<div class="clear"></div>
 

</div>
<!--  end content-table-inner  -->
</td>
<td id="tbl-border-right"></td>
</tr>
<tr>
	<th class="sized bottomleft"></th>
	<td id="tbl-border-bottom">&nbsp;</td>
	<th class="sized bottomright"></th>
</tr>
</table>




<div class="clear">&nbsp;</div>

</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer -->

 <?php 
 require_once("templates/bas.php");
 
 // on supprime le fichier tempo
 unlink($fichier_temporaire);