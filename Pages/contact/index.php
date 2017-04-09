<?php 

if(isset($_POST['email']) and isset($_POST['sujet']) and isset($_POST['message']))
{
	$erreur = false;
	$message="";
	$destinataire = 'dragonralph@gmail.com';
	
	$email = htmlentities($_POST['email']);
	if(!preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i',str_replace('&amp;','&',$email)))
	{
	
		$erreur=true;
		$message.="L'email est invalide !"."<br />";
	
	}
	
	if(empty($_POST['sujet']) or strlen($_POST['sujet'])<5){
		$erreur=true;
		$message.="Le sujet doit comporter 5 caract&egrave;res minimum !"."<br />";
	
	}
	
	if(empty($_POST['message']) or strlen($_POST['message'])<10){
		$erreur=true;
		$message.="Le message doit comporter 10 caract&egrave;res minimum !"."<br />";
	
	}
	
	
	
	
	if($erreur==false){
		$sujet = 'WebiTools: '.stripslashes($_POST['sujet']);
		$message = stripslashes($_POST['message']);
		$headers = "From: <".$email.">\n";
		$headers .= "Reply-To: ".$email."\n";
		$headers .= "Content-Type: text/plain; charset=\"iso-8859-1\"";
		if(mail($destinataire,$sujet,$message,$headers))
		{
			$ok=true;
				
		}
		else
		{
			$erreur=true;
			$message="Une erreur s'est produite...";
		}
	}
	
}


$titre = "Contact";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Contact</h3>
	   
		<form action="/contact" method="post">
			<br />
			
			<?php 
			if(isset($erreur)&&$erreur){?>
				<p class="erreur">
					<?=$message;?>
				</p>
			<?php } if(!isset($ok)) {?>
				<p>
					Une question ? Une suggestion ? N'h&eacute;sitez pas &agrave; me contacter !<br /><br />
				</p>
				
				
					<label for="email">Votre Email :</label> <input type="text" name="email" id="email" /><br />
					<label for="sujet">Sujet du message :</label> <input type="text" name="sujet" id="sujet" /><br />
					<label for="message">Message:</label><br />
					<textarea cols="70" rows="4" name="message" id="message"></textarea><br />
					<input type="submit" value="Envoyer" />
			</form>
			
			<?php } else {
			?>
				<p class="confirm">
					Votre message a &eacute;t&eacute; envoy&eacute; !
				</p>
			<?php } ?>
			
   </div>
<?php 
require_once('templates/bas.php');

