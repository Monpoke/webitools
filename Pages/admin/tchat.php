<?php 
if(getParam(1)=="messages"){
	$type=2;
	
		
	$retour_messages = Query("SELECT tchat_messages.*, sites.nom, sites.namespace FROM tchat_messages, sites WHERE tchat_messages.id_site = sites.id_site or (  tchat_messages.id_site='0' and sites.id_site='1') ORDER BY id DESC LIMIT 30");
	
	if(mysql_num_rows($retour_messages)==0){
		echo "<b>Aucun message encore...</b>";
	
	}
	else {
		$nv=false;
		while($do=mysql_fetch_array($retour_messages)){
			
			$c = "<a title='Site: {$do['nom']} || {$do['namespace']}' href='/admin/tchat/mdelete/".$do['id']."' onclick='return(confirm(\"Confirmer la suppression ?\"))'>X</a>";
			
			// preg_match
			$message=$do['message'];
			
			$message = preg_replace("#&lt;b&gt;(.+)&lt;/b&gt;#Usi", "<b>$1</b>", $message);
			$message = preg_replace("#&lt;i&gt;(.+)&lt;/i&gt;#Usi", "<i>$1</i>", $message);
			$message = preg_replace("#&lt;u&gt;(.+)&lt;/u&gt;#Usi", "<u>$1</u>", $message);
			
			if($do['etat']==2)
				$message = "<i style='color: red'>$message</i>";
			
			if(!preg_match("#^/me (.+)#Usi", $message))
				echo "$c <b title='Site: {$do['nom']} || {$do['namespace']}'>".converseDate($do['date_post'], 9). "</b> par <i title='Site: {$do['nom']} || {$do['namespace']} || Ip cryptee : {$do['ip']} '>".$do['pseudo']." :</i> ".$message."<br />";
			else{
				$message = substr($message, 3);
				echo "$c <b>".converseDate($do['date_post'], 11). "</b> : <i style='color:purple'>".$do['pseudo']." $message </i><br />";
			
			}
			if(isset($_SESSION['dernier_message']) && $_SESSION['dernier_message'] < $do['id'] && !$nv){
				echo '<hr />';
				$nv=true;
			}
			
			if(!isset($_SESSION['dernier_message']))
				$_SESSION['dernier_message'] = $do['id']; // on recupere le dernier message
			elseif($do['id']>$_SESSION['dernier_message'])
				$_SESSION['dernier_message'] = $do['id'];
		
		}
	
		echo "<span id='bas'></span>";
	}
	
	
	exit;

}


elseif(getParam(1)=="poster"){
	$nombre_secondes = 5;
	$max_posts = 2;
	
	
	if(!empty($_POST['message'])){
		$message = Secure($_POST['message']);
		$pseudo = "Monpoke";
		
		$cible = Secure($_POST['dest']);
		
		if($message=="/purge deleted"){
			Query("DELETE FROM tchat_messages WHERE etat='2'");
			exit("ok");
		}
		
		
		if(!empty($cible)){
			if($cible!="monpoke")
				$retour = Query("SELECT * FROM sites WHERE service_tchat='oui' and namespace='$cible'");
			
			if($cible=="monpoke" or mysql_num_rows($retour)==1){
				
				if($cible!="monpoke"){
					$id_site = mysql_fetch_array($retour);
					$id_site = $id_site['id_site'];
				} else {
					$id_site = 1;
				
				}
				
			
				
				if($message=="/purge"){
					// Query("UPDATE tchat_messages SET etat='2'  WHERE id_site='$id_site'");
					exit("ok");
				}
				
				
				
				
				elseif(preg_match("#^\/(un)?ban (.+)?#Usi", $message)){
				
					if(preg_match("#^\/ban (.+)#Usi", $message)){
					
						$n = substr($message, 5);
						
						// on recherche le dernier message du membre
						$ps = Secure($n);
						
						$retour  =Query("SELECT * FROM tchat_messages WHERE pseudo='$ps' and id_site='$id_site' ORDER BY id DESC LIMIT 1");
						
						if(mysql_num_rows($retour)==1){
							
							$do = mysql_fetch_array($retour);
							
							if(empty($do['ip']))
								exit("Le membre a été banni partiellement (Ip manquante)");
							
							else {
								// on verifie que le membre n'est pas banni
								
								$retour = Query("SELECT * FROM bans_chat WHERE id_site='$id_site' and (pseudo='$ps' or ip='{$do['ip']}')");
								
								if(mysql_num_rows($retour)!=0)
									exit("Le membre est déjà banni :D");
								else {
									Query("INSERT INTO bans_chat(id_site, ip, pseudo) VALUES('$id_site', '{$do['ip']}', '$ps')");
									
									exit("$ps a été banni :)");
									
								
								}
							}
						
						} else {
							exit("Le pseudo est inconnu ;)");
						}
						
						
						exit("ban sur $n");
					}
					else {
							$n = substr($message, 7);
							
							// on recherche le ban du membre
							$ps = Secure($n);
							
							$retour = Query("SELECT * FROM bans_chat WHERE id_site='$id_site' and pseudo='$ps'");
							
							if(mysql_num_rows($retour)>0){
								$count=0;
								while($doo=mysql_fetch_array($retour)){
									
									Query("DELETE FROM bans_chat WHERE id='{$doo['id']}' and id_site='$id_site'");
									$count++;
								}
								
								
								if($count>0){
									exit("$ps a été libéré de prison :)");
								}
							} else
								exit("Ce membre n'est pas en prison !");
							
							
						exit("Ban sur $n");
						
					}
					
					exit('Hum Oo');
				}
				
				
				
				$ip = substr(cryptage($_SERVER['REMOTE_ADDR']), 0, 10);
		
				
				Query("INSERT INTO tchat_messages(id_site, date_post, message, pseudo, ip) VALUES('$id_site', '".time()."', '$message', '$pseudo', '$ip')");
			} else
				exit("Le site cible est introuvable ou n'a pas active le module");
		
		}
		
		else {
			if($message=="/purge"){
				Query("UPDATE tchat_messages SET etat='2' ");
				exit("ok");
			}
			
			elseif(preg_match("#^\/(un)?ban (.+)?#Usi", $message)){
				
					if(preg_match("#^\/ban (.+)#Usi", $message)){
					
						$n = substr($message, 5);
						
						// on recherche le dernier message du membre
						$ps = Secure($n);
						
						$retour  =Query("SELECT * FROM tchat_messages WHERE pseudo='$ps' and id_site='0' ORDER BY id DESC LIMIT 1");
						
						if(mysql_num_rows($retour)==1){
							
							$do = mysql_fetch_array($retour);
							
							if(empty($do['ip']))
								exit("Le membre a été banni partiellement (Ip manquante)");
							
							else {
								// on verifie que le membre n'est pas banni
								
								$retour = Query("SELECT * FROM bans_chat WHERE id_site='0' and (pseudo='$ps' or ip='{$do['ip']}')");
								
								if(mysql_num_rows($retour)!=0)
									exit("Le membre est déjà banni :D");
								else {
									Query("INSERT INTO bans_chat(id_site, ip, pseudo) VALUES('0', '{$do['ip']}', '$ps')");
									
									exit("$ps a été banni :)");
									
								
								}
							}
						
						} else {
							exit("Le pseudo est inconnu ;)");
						}
						
						
						exit("ban sur $n");
					}
					else {
							$n = substr($message, 7);
							
							// on recherche le ban du membre
							$ps = Secure($n);
							
							$retour = Query("SELECT * FROM bans_chat WHERE id_site='0' and pseudo='$ps'");
							
							if(mysql_num_rows($retour)>0){
								$count=0;
								while($doo=mysql_fetch_array($retour)){
									
									Query("DELETE FROM bans_chat WHERE id='{$doo['id']}' and id_site='0'");
									$count++;
								}
								
								
								if($count>0){
									exit("$ps a été libéré de prison :)");
								}
							} else
								exit("Ce membre n'est pas en prison !");
							
							
						exit("Ban sur $n");
						
					}
					
					exit('Hum Oo');
				}
				
			
			Query("INSERT INTO tchat_messages(id_site, date_post, message, pseudo ) VALUES('0', '".time()."', '$message', '$pseudo')");
		
		}
		
			
		exit("ok");
	
	}
	
	else
		echo "Veuillez remplir le champ avant d'envoyer :)";
	exit;
	 
}


elseif(getParam(1)=="mdelete"){
	$id=intval(getParam(2));
	Query("DELETE FROM tchat_messages WHERE id='$id'");
	
}


$titre = "Panel admin";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Monitoring des messages</h3>
	   
		
		<div id='tchat' style="width: 600px">


			<div id="tchat_messages" style="max-height: 200px; width:600px; overflow: auto;">
				Chargement...
			</div>
			
			<div id="tchat_post" style=" width:600px; ">
				<form action="javascript: sendMsg(); " method="post" id="Form_Tchat" name="form_tchat">
					<p>
						<br />
						<label for="champ_message">Votre message :</label> <input class="personnalisation_input" type="text" id="champ_message" name="message" /> <input type="button" value="Envoyer" onclick="javascript: sendMsg();" /><br />
						<label for="champ_to">Site cibl&eacute; :</label>  <input class="personnalisation_input" type="text" id="champ_to" name="champ_to" />
						<br /><br />
						
						<u>Commandes disponibles</u><br />
						
							- &lt;b&gt;Texte&lt;/b&gt; = <b>Texte</b><br />
							- &lt;i&gt;Texte&lt;/i&gt; = <i>Texte</i><br />
							- &lt;u&gt;Texte&lt;/u&gt; = <u>Texte</u><br />
						<br />
						
						
					</p>
				</form>
			</div>
			
			<script type="text/javascript">
				var ba=false;
				var descendre=false;
				$(function(){

					
					actualiserTchat();  
							



				});


				function actualiserTchat() {
					$.ajax({
					url: "/admin/tchat/messages#bas",
					ifModified:true,
					success: function(content){
						$('#tchat_messages').html(content);
						
						// if(descendre)
							// $("#tchat_messages").scrollTop(55555000);
						
						setTimeout('actualiserTchat()',3000); 
					}
					});
				}
				
				function sendMsg(){
					$.post('/admin/tchat/poster',{
						message: $('#champ_message').val(),
						dest: $('#champ_to').val()
					},function(data){
						$('#champ_message').val("")
						if(data!="ok")
							alert(data);
					});
				}

				$("#tchat_messages").hover(function(){

					descendre=false;
					
				});

				$("#tchat_messages").mouseout(function(){

					descendre=true;
					
				});



				</script>
			
		</div>
		
		
	   
   </div>
<?php 
require_once('templates/bas.php');


