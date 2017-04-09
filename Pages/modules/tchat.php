<?php 
$id_site = intval(getParam(1));
$_SERVER['REMOTE_ADDR']= $_SERVER['HTTP_X_CLIENT_IP'];


$retour_site = Query("SELECT * FROM sites, membres WHERE sites.id_site='$id_site' and sites.etat='2' and membres.id = sites.id_membre");

if(mysql_num_rows($retour_site)!=1){
	Redirect("/", 5);
	$titre="Module de tchat";
	require('templates/haut_mod.php');
	?>
			
		<div class="bloc_erreur">
			Le site en question n'existe pas !<br /><br />
			
			<a href="/" onclick="javascript:history.go(-1)">Retour</a>
		</div>
			
			
	<?php 
	require('templates/bas_mod.php');
	exit;

	
}
	
$donnees=mysql_fetch_array($retour_site);


if($donnees['service_tchat']=='non'){
Redirect("/", 5);
$titre="Module de tchat";
require('templates/haut_mod.php');
?>
		
	<div class="bloc_erreur">
		Le webmaster du dit-site n'a pas activ&eacute; ce module.<br /><br />
		
		<a href="/" onclick="javascript:history.go(-1)">Retour</a>
	</div>
		
		
<?php 
require('templates/bas_mod.php');
exit;
} 

$conf=mysql_fetch_array(Query("SELECT * FROM tchat WHERE id_site='$id_site'"));


// VERIF Membre connecte
if(!isset($_SESSION['auth_'.$id_site])){

	if($donnees['service_connexion']=="non"){
	
		exit("Oups :/ La connexion personnalis&eacute;e doit &ecirc;tre install&eacute;e et activ&eacute;e :/");
	
	}
	else
		Redirect("/modules/connexion/$id_site/tchat");
		
}


// sinon il est connecte go !


if(empty($conf['id_page'])){
	exit("Verifiez l'id de la page");
}



// on verifie le ban
if(!isset($_SESSION['auth_'.$id_site]['tchat']['banni']))
	$_SESSION['auth_'.$id_site]['tchat']['banni']=false;


$mon_ip = substr(cryptage($_SERVER['REMOTE_ADDR']), 0, 10);
$mon_pseudo = Secure($_SESSION['auth_'.$id_site]['Pseudo']);

if(!isset($_SESSION['auth_'.$id_site]['tchat']['verif_ban']))
	$_SESSION['auth_'.$id_site]['tchat']['verif_ban'] = 1;
	
// on verifie un bann potentiel
if($_SESSION['auth_'.$id_site]['tchat']['verif_ban']==1){
	$retour_ban = Query("SELECT * FROM bans_chat WHERE (id_site='$id_site' or id_site='0') && (ip='$mon_ip' OR pseudo='$mon_pseudo')");

	if(mysql_num_rows($retour_ban)>0){
		$_SESSION['auth_'.$id_site]['tchat']['banni']=true;
	
	} else
		$_SESSION['auth_'.$id_site]['tchat']['banni']=false;
}

else {
	if($_SESSION['auth_'.$id_site]['tchat']['verif_ban']==3) // au bout de 3 actus, on regarde
	$_SESSION['auth_'.$id_site]['tchat']['verif_ban']=1;
	else
		$_SESSION['auth_'.$id_site]['tchat']['verif_ban']++;
}


/* on verifie une action */

if(getParam(2)=="messages"){

	if(isset($_SESSION['auth_'.$id_site]['tchat']['banni']) && $_SESSION['auth_'.$id_site]['tchat']['banni'])
		exit("Allez directement en prison !");
		
	$type = $cle = getParam(3);
	$pseudo = Secure($_SESSION['auth_'.$id_site]['Pseudo']);
	
	if($pseudo === "Monpoke" &&  gethostbyname('monpoke.dyndns.org') === $_SERVER['REMOTE_ADDR']){
			$type = "admin";
		} elseif($type==="admin")
			$type = "";
	
	if($type === "admin" || $type==$conf['cle_modo'].substr(sha1($_SERVER['REMOTE_ADDR']), 5))
		$type=2;
	else
		$type=1;
		
	if(strtolower($conf['champ_1'])=="desc")
		$ordre = "DESC";
	else
		$ordre = "ASC";
	
	
	
	$retour_messages = Query("SELECT * FROM tchat_messages WHERE etat='1' and (id_site='$id_site' or id_site='0') ORDER BY id $ordre");
	
	if(mysql_num_rows($retour_messages)==0){
		echo "<b>Aucun message encore...</b>";
	
	}
	else {
		$nombre_total_de_messages = mysql_num_rows($retour_messages);
		$nombre_maximum_de_messages = 40;
		
		// calcul du rang a passer
		if($conf['champ_1'] !== "desc") { // donc asc
			$passTo = $nombre_total_de_messages-$nombre_maximum_de_messages;
		}
		
		
		$i=1;
		while($do=mysql_fetch_array($retour_messages)){
			$c=$t="";
			if($i < $passTo){
				$i++;
				continue;
			}
			
			if($type==2) {
				$c = "<a href='/modules/tchat/$id_site/mdelete/".$do['id']."/$cle' onclick='return(confirm(\"Confirmer la suppression ?\"))'>X</a>";
				
				if(!empty($do['ip']))
					$t = " title='IP : ".$do['ip']."'";
				else
					$t = " title='IP : n/a'";
			}
			
			// preg_match
			$message=$do['message'];
			
			$message = preg_replace("#&lt;b&gt;(.+)&lt;/b&gt;#Usi", "<b>$1</b>", $message);
			$message = preg_replace("#&lt;i&gt;(.+)&lt;/i&gt;#Usi", "<i>$1</i>", $message);
			$message = preg_replace("#&lt;u&gt;(.+)&lt;/u&gt;#Usi", "<u>$1</u>", $message);
			$message = preg_replace('#http(s)?://[a-z0-9._/-]+(\?(.+))?#i', '<a href="$0" target="_blank" onclick="return(confirm(\'Ouvrir ce lien ?\')); return false">$0</a>', $message);

			if(empty($_SESSION['auth_'.$id_site]['tchat']['ignores']) || !in_array( strtolower($do['pseudo']) ,$_SESSION['auth_'.$id_site]['tchat']['ignores'])){
				
				
				
				if(!preg_match("#^/me (.+)#Usi", $message)){
					if($do['moderateur']=="0" && $do['pseudo'] !== "Monpoke" || $do['pseudo'] === "Monpoke")
						echo "$c <b>".converseDate($do['date_post'], 11). "</b> par <i $t>".$do['pseudo']." :</i> ".$message."<br />";
					else
						echo "$c <b>".converseDate($do['date_post'], 11). "</b> par <i ><b title='Modérateur'>".$do['pseudo']."</b> :</i> ".$message."<br />";
					
				}
				else {
					$message =substr($message, 3);
					if($do['moderateur']=="0" && $do['pseudo'] !== "Monpoke" || $do['pseudo'] === "Monpoke")
						echo "$c <b$t>".converseDate($do['date_post'], 11). "</b> : <i style='color:purple'>".$do['pseudo']." $message </i><br />";
					else
						echo "$c <b$t>".converseDate($do['date_post'], 11). "</b> : <i style='color:purple;'><b title='Modérateur' style='text-decoration:underline'>".$do['pseudo']."</b> $message </i><br />";
					
				}
			}
		
			$i++;
		}
		
		
	}
	
	
	exit;

}



elseif(getParam(2)=="poster"){
	$nombre_secondes = 5;
	$max_posts = 2;
	
	if(isset($_SESSION['auth_'.$id_site]['tchat']['banni']) && $_SESSION['auth_'.$id_site]['tchat']['banni'])
		exit("Allez directement en prison !");
	
	if(!empty($_POST['message'])){
		$message = Secure($_POST['message']);
		$pseudo = Secure($_SESSION['auth_'.$id_site]['Pseudo']);
		
		if($pseudo === "Monpoke" && gethostbyname('monpoke.dyndns.org') === $_SERVER['REMOTE_ADDR']){
			$type = "admin";
		}
		
		$type = $cle = getParam(3);
		if($type==="admin" || $type==$conf['cle_modo'].substr(sha1($_SERVER['REMOTE_ADDR']), 5)){
			if($message=="/purge"){
				Query("UPDATE tchat_messages SET etat='2'  WHERE id_site='$id_site'");
				exit("ok");
			}
			
			elseif($message=="/banlist"){
				$bannis = Query("SELECT * FROM bans_chat WHERE id_site='$id_site'");
				
				if(mysql_num_rows($bannis)>0){
					while($d=mysql_fetch_array($bannis)){
						echo $d['pseudo']."\n";
					
					}
					exit;
				
				}
				else
					exit("Aucun banni :)");
					
			}
			
			elseif(preg_match("#^\/(un)?ban (.+)?#Usi", $message)){
				
					if(preg_match("#^\/ban (.+)#Usi", $message)){
					
						$n = substr($message, 5);
						
						// on recherche le dernier message du membre
						$ps = Secure($n);
						
						if(strtolower($ps)=="monpoke")
							exit("Monpoke ne peut être banni :D");
						elseif(strtolower($mon_pseudo) == strtolower($ps))
							exit("Vous ne pouvez vous bannir !");
							
						$retour  =Query("SELECT * FROM tchat_messages WHERE pseudo='$ps' and id_site='$id_site' ORDER BY id DESC LIMIT 1");
						
						if(mysql_num_rows($retour)==1){
							
							$do = mysql_fetch_array($retour);
							
							if(empty($do['ip'])){	
								$retour = Query("SELECT * FROM bans_chat WHERE id_site='$id_site' and (pseudo='$ps' )");
								
								if(mysql_num_rows($retour)!=0)
									exit("Le membre est déjà banni :D");
									
								Query("INSERT INTO bans_chat(id_site, pseudo) VALUES('$id_site', '$ps')");
									
								exit("Le membre a été banni partiellement (Ip manquante)");
							
							
							
							}
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
						
						if(strtolower($ps)=="monpoke")
							exit("Monpoke ne peut être banni :D");
						elseif(strtolower($mon_pseudo) == strtolower($ps))
							exit("Vous ne pouvez vous bannir !");
							
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
		
			$moderation="1";
		} else
			$moderation="0";
		
		
		if($message=="/ignores"){
			if(empty($_SESSION['auth_'.$id_site]['tchat']['ignores']))
				exit("Personne n'est ignoré !");
			else{
				echo(implode("\n", $_SESSION['auth_'.$id_site]['tchat']['ignores']));
				exit;
				
			}
		
		}
		elseif($message=="/videignores"){
			if(isset($_SESSION['auth_'.$id_site]['tchat']['ignores'])) {
				unset($_SESSION['auth_'.$id_site]['tchat']['ignores']);
				
			}	
			exit("Opération effectuée !");
		}
		
		elseif(preg_match("#^\/(un)?ignore (.+)$#Usi", $message)){
			if(preg_match("#^\/ignore (.+)$#", $message)){
				$n = substr($message, 8);
				
				if(strtolower($n) == strtolower($_SESSION['auth_'.$id_site]['Pseudo'])){
					exit("Vous ne pouvez vous ignorer vous-même !");
					
					
				}
				elseif(strtolower($n) == strtolower("Monpoke")){
					exit("Vous ne pouvez ignorer Monpoke !");
					
					
				}
				elseif(empty($_SESSION['auth_'.$id_site]['tchat']['ignores'])){
					$_SESSION['auth_'.$id_site]['tchat']['ignores'][] = strtolower($n);
					exit("$n a été ignoré");
				} else {
					if(in_array( strtolower($n) ,$_SESSION['auth_'.$id_site]['tchat']['ignores']))
						exit("$n est déjà ignoré !");
					else {
						$_SESSION['auth_'.$id_site]['tchat']['ignores'][] = strtolower($n);
						exit("$n a été ignoré");
					}
				}
				
			} else {
				
				$n = substr($message, 10);
				
				if(!empty($_SESSION['auth_'.$id_site]['tchat']['ignores']) && in_array( strtolower($n) ,$_SESSION['auth_'.$id_site]['tchat']['ignores'])){
					unset($_SESSION['auth_'.$id_site]['tchat']['ignores'][array_search( strtolower($n), $_SESSION['auth_'.$id_site]['tchat']['ignores'])] );
					exit("$n a été dés-ignoré");
				} else 
					exit("$n n'est pas ignoré");
					
				
			
			}
		}
		
		
		// verif vocabulaire
		$mots_grossiers = array(
			'pd',
			'gueule',
			'connar',
			'conar',
			'conas',
			'connas',
			'batar'
			
		
		
		);
		
		foreach($mots_grossiers as $mot){
			if(preg_match("#$mot#Usi", $message)){
				exit("Faites attention à votre langage !");
				
			}
			
		}
		
		
		
		// verif anti flood
		$retours = Query("SELECT * FROM tchat_messages WHERE etat='1' and id_site='$id_site' ORDER BY id DESC LIMIT 3");
		$moi=true;
		$time=time();
		$temps_dernier_msg = 60;
		while($do=mysql_fetch_array($retours)){
			$temps_dernier_msg = $time-$do['date_post'];
			
			if($do['pseudo']!=$pseudo  or $temps_dernier_msg > 15)
				$moi = false;
				
		}
		
		if(mysql_num_rows($retours)==0)
			$moi=false;
			
		
		$ip = substr(cryptage($_SERVER['REMOTE_ADDR']), 0, 10);
		
		if(!$moi)
			Query("INSERT INTO tchat_messages(id_site, date_post, message, pseudo, ip, moderateur) VALUES('$id_site', '".time()."', '$message', '$pseudo', '$ip', '$moderation')");
		else
			exit("3 messages en 15s maxi");
		
			
		exit("ok");
	
	}
	
	else
		echo "Veuillez remplir le champ avant d'envoyer :)";
	exit;
	 
}




elseif(getParam(2)=="mdelete" && (getParam(4) == $conf['cle_modo'].substr(sha1($_SERVER['REMOTE_ADDR']), 5) OR (Secure($_SESSION['auth_'.$id_site]['Pseudo']) === "Monpoke" && gethostbyname('monpoke.dyndns.org') === $_SERVER['REMOTE_ADDR']))){
	$id=intval(getParam(3));
	Query("UPDATE tchat_messages SET etat='2' WHERE id_site='$id_site' and id='$id'");
}






// on retourne la page telle que la voit le mmebre

// Submit those variables to the server
$infos_connect = array(
    'Pseudo' => $_SESSION['auth_'.$id_site]['Pseudo'],
    'Password' => base64_decode($_SESSION['auth_'.$id_site]['Password']),
);


$dir = ROOT. "/tmp/".sha1($_SERVER['REMOTE_ADDR'])."_tchat".rand(0,99).".tmp";

// CONNEXION
$ch = curl_init();
curl_setopt($ch, CURLOPT_COOKIEJAR, $dir);
curl_setopt($ch, CURLOPT_COOKIEFILE, $dir);
curl_setopt($ch, CURLOPT_URL,"http://www.webidev.com/{$donnees['namespace']}/MConnect");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
curl_setopt($ch, CURLOPT_USERAGENT, "WebiTools");
curl_setopt($ch, CURLOPT_POSTFIELDS, "Pseudo={$infos_connect['Pseudo']}&Password={$infos_connect['Password']}");

ob_start();
	echo curl_exec ($ch); 
$t=ob_get_clean();

curl_close ($ch);

unset($ch);


// recupere le tchat
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
// curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);	
curl_setopt($ch, CURLOPT_COOKIEFILE, $dir);
curl_setopt($ch, CURLOPT_COOKIEJAR, $dir);
curl_setopt($ch, CURLOPT_URL,"http://www.webidev.com/{$donnees['namespace']}/ViewPage?Id={$conf['id_page']}");

$retour = curl_exec($ch);

curl_close ($ch);

unset($ch);

// DECONNEXION
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);	
curl_setopt($ch, CURLOPT_COOKIEFILE, $dir);
curl_setopt($ch, CURLOPT_COOKIEJAR, $dir);
curl_setopt($ch, CURLOPT_URL,"http://www.webidev.com/{$donnees['namespace']}/MDeco");

curl_exec($ch);

curl_close ($ch);
unset($ch);
if(file_exists($dir))
	unlink($dir);




if(!preg_match("#\[WT:Tchat\]#Usi", $retour) && !preg_match("#\[WT:AdminTchat\]#Usi", $retour)){

	exit("La balise [WT:Tchat] n'a pas été trouvée");


}
elseif(is_modo())
	$mode=2;
elseif(preg_match("#\[WT:Tchat\]#Usi", $retour) OR (preg_match("#\[WT:Tchat\]#Usi", $retour) && preg_match("#\[WT:AdminTchat\]#Usi", $retour))){
	$mode = 1; // mode par defaut

}
elseif(preg_match("#\[WT:AdminTchat\]#Usi", $retour)){
	$mode=2;
	
}



if($mode==1)
	$moderateur="0";
else
	$moderateur="1";



$pub = <<<EOF

EOF;

$retour = str_replace('UA-22532783-1', 'UA-34275806-2', $retour);


$compa = <<<EOG

/prototype.js"></script>

<script type="text/javascript" src="/js/jquery-1.6.1.min.js"></script>

<script type="text/javascript">
 var \$j = jQuery.noConflict();
 
 
</script>


<style type="text/css">

	#pub {
		position: fixed;
		bottom:0;
		left: 0;
		padding: 5px;
		-webkit-border-top-right-radius: 5px;
		-moz-border-radius-topright: 5px;
		border-top-right-radius: 5px;
		background: #ffffff;
		color: #000000;
	
	}
		
	#tchat_messages {
		max-height: 200px; 
	
		overflow: auto; 
		/*position:absolute;
		top: 0; 
		height: 100%; 
		width: 100%; */
	}
	
	.personnalisation_input {
		width: 60%;
	
	}
		
</style>

EOG;




if($conf['champ_1']!="desc"){
	$msg = <<<EOF
if(descendre==true && figer==false){
	\$j("#tchat_messages").scrollTop(55555000); 
}




EOF;
	
	
$msg2 = <<<EOF

 <input type="button" id="figer" value="Figer" onclick="javascript: inverseFige();" /> 

EOF;
	
$msg3 = <<<EOF

 <i><small>Un bouton "Figer" est disponible pour emp&ecirc;cher le tchat de remonter :)</small></i><br />
EOF;
	
	
	
}
else {
	$msg  = "";
	$msg2  = "";
	$msg3 = "";
	
}

$info="";
$comp="";

if($mode==2){
	$info = <<<EOF
<u>Commandes mod&eacute;rateur</u> :<br />
- <i>/purge</i> pour effacer tous les messages<br />
- <i>/ban pseudo</i> pour bannir un membre du tchat<br />
- <i>/unban pseudo</i> pour d&eacute;bannir un membre du tchat<br />
- <i>/banlist</i> pour voir la liste des bannis<br />
EOF;


	$comp = "/".$conf['cle_modo'].substr(sha1($_SERVER['REMOTE_ADDR']), 5);
	
}
	
	
$script = <<<EOF
<script type="text/javascript">
var ba=false;
var descendre=true;
var figer=false;

\$j(function(){

	
	actualiserTchat();  
			
	setInterval('actualiserTchat()',3000); 



});


function actualiserTchat() {
	\$j.ajax({
	url: "/modules/tchat/$id_site/messages$comp",
	ifModified:true,
	success: function(content){
		\$j('#tchat_messages').html(content);
		
		$msg
			
		
	}
	});
}
function sendMsg(){
	\$j.post('/modules/tchat/$id_site/poster$comp',{
		message: \$j('#champ_message').val()
	},function(data){
		\$j('#champ_message').val("")
		if(data!="ok") {
			alert(data);
		
		} else {
			actualiserTchat();
		}
	});
}

function inverseFige(){
	if(descendre){
		\$j("#figer").val('Défiger');
		descendre=!descendre;
		figer=true;
	}
	else {
		\$j("#figer").val('Figer');
		descendre=!descendre;
		figer=false;
	
	}

}

\$j("#tchat_messages").hover(function(){
	
	if(figer==false)
		descendre=false;
	
});

\$j("#tchat_messages").mouseout(function(){

	if(figer==false)
	descendre=true;
	
});



</script>



</body>
EOF;


$pseudo = Secure($_SESSION['auth_'.$id_site]['Pseudo']);
$interface = <<<EOG

<div id='tchat'>


	<div id="tchat_messages">
		Chargement...
	</div>
	
	<div id="tchat_post">
		<form action="javascript: sendMsg(); " method="post" id="Form_Tchat" name="form_tchat">
			<p>
				<br />
				<label for="champ_message">Votre message :</label> <input class="personnalisation_input" type="text" id="champ_message" name="message" /> <input type="button" value="Envoyer" onclick="javascript: sendMsg();" />
				$msg2<br />
				$msg3<br />
				
				<u>Commandes disponibles</u> :<br />
				
					- &lt;b&gt;Texte&lt;/b&gt; = <b>Texte</b><br />
					- &lt;i&gt;Texte&lt;/i&gt; = <i>Texte</i><br />
					- &lt;u&gt;Texte&lt;/u&gt; = <u>Texte</u><br />
					- <i>/me MESSAGE</i> = <i>{$pseudo}</i> message <br />
					- <i>/ignore pseudo</i> = Les messages de <i>pseudo</i> ne sont plus affich&eacute;s <br />
					-  <i>/unignorepseudo</i> = Les messages de <i>pseudo</i> sont affich&eacute;s<br />
					- <i>/ignores</i> = Affiche la liste des ignor&eacute;s<br />
					- <i>/videignores</i> = Vide la liste des ignor&eacute;s<br />
				<br />
				
				
				$info
			</p>
		</form>
	</div>
	
	
	
</div>



EOG;








$retour = preg_replace('#/prototype.js"></script>#Usi', $compa, $retour);
if($mode==1)
	$retour = preg_replace("#\[WT:Tchat\]#Usi", $interface, $retour);
else {
	$retour = preg_replace("#\[WT:AdminTchat\]#Usi", $interface, $retour);
	$retour = preg_replace("#\[WT:Tchat\]#Usi", $interface, $retour);
	
}
$retour = preg_replace("#</body>#", $script, $retour);




// $retour = preg_replace("#_gaq.push(\['_setAccount#Usi', 'UA\-22532783\-1'\]);#", $pub, $retour);

echo $retour;
 
 

if($donnees['pub']=="oui" && PUB_PLUME){?>
	<div id="pub"><a href="http://plumedor.fr">Pub : <b>Plume d'Or</b> ouvre en <b>b&ecirc;ta-test</b></a></div>
	
<?php }elseif($donnees['pub']=="oui"){?>
	<div id="pub"><?=PUBLICITE;?></div>
	
<?php }elseif($donnees['type']=='demo'){?>
	<div id="pub">
		Version de d&eacute;monstration<br />
		Les images appartiennent &agrave; leur auteurs respectifs.
	</div>
<?php } ?>
</div>

<script type="text/javascript">

	$j(function(){
	
		var couleur = $j(".trcorpspage").css('color');
		var bgimg = $j(".trcorpspage").css('background-image');
		var bgcolor = $j(".trcorpspage").css('background-color');
		
		
		
		$j("#pub").css('color', couleur);
		$j("#pub").css('background-color', bgcolor);
		$j("#pub").css('background-image', bgimg);
		
		
		
	
	
	});

</script>