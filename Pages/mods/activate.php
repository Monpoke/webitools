<?php 

$id_site = intval(getParam(2));
$module = getParam(1);

if(!in_array($module, $modules_dispos))
	Redirect("/panel");

if(!is_modo()){
	$cond = "sites.id_membre='".get('id')."'";
	
} else {
	
	if(is_modo(true))
		$cond = "membres.rang < '".MODERATEUR."'";
	else
		$cond = "membres.rang<='".ADMINISTRATEUR."'";
		
}

$retour_site = Query("SELECT * FROM sites, membres WHERE sites.id_site='$id_site' and $cond and sites.etat='2' and membres.id = sites.id_membre");

if(mysql_num_rows($retour_site)!=1)
	Redirect("/panel");

	
$donnees=mysql_fetch_array($retour_site);


if(getEtat($donnees, $module, 1)==false)
	Redirect("/website/gerer/$id_site");


// par defaut
$etat="non";


$titre = "Activer un module";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Activer un module</h3>
	   <br />
	   
	   <?php 
	   if($module=="vote"){
	   
			if(getEtat($donnees, "vote", 1)=="oui"){
				$etat="non";
				
				Query("DELETE FROM votes WHERE id_site='$id_site'");
				?>
					
					<p>
						Le module de vote entre Webmaster Webidev a &eacute;t&eacute; d&eacute;sactiv&eacute; !
					
					</p>
					
				<?php
			}
			
			elseif(getEtat($donnees, "vote", 1)=="non"){
				$etat="oui";
				?>
					
					<p>
						Le module de vote entre Webmaster Webidev a &eacute;t&eacute; activ&eacute; !<br /><br />
						
						Voici le code &agrave; inclure sur la page de votre choix :<br />
						<br />
						<code onclick="this.select();">
							&lt;a <?=HTTP_PUBLIC;?>modules/vote/<?=$id_site;?>&gt;Votez pour nous !&lt;/a&gt;
						</code>
					
					</p>
					
				<?php
			}
			
			

	   ?>
			
			
			
	   <?php }
	    elseif($module=="inscription"){
	   
			if(getEtat($donnees, "inscription", 1)=="oui"){
				$etat="non";
				
				Query("DELETE FROM inscriptions WHERE id_site='$id_site'");
				?>
					
					<p>
						Le module d'inscription personnalis&eacute;e a &eacute;t&eacute; d&eacute;sactiv&eacute; !
					
					</p>
					
				<?php
			}
			
			elseif(getEtat($donnees, "inscription", 1)=="non"){
				$etat="oui";
				
				Query("INSERT INTO inscriptions(id_site, date_modif) VALUES('$id_site', '".time()."')");
				?>
					
					<p>
						Le module d'inscription personnalis&eacute;e a &eacute;t&eacute; activ&eacute; !<br /><br />
						
						Voici le code &agrave; inclure sur la page de votre choix :<br />
						<br />
						<code onclick="this.select();">
							&lt;a <?=HTTP_PUBLIC;?>modules/inscription/<?=$id_site;?>&gt;Inscrivez vous d&egrave;s maintenant !&lt;/a&gt;
						</code>
					
					</p>
					
				<?php
			}
			
			

	   ?>
			
			
			
	   <?php }
	   
	   elseif($module=="connexion"){
		
			if(getEtat($donnees, "connexion", 1)=="oui"){
				$etat="non";
				
				Query("DELETE FROM connexions WHERE id_site='$id_site'");
				
				?>
					
					<p>
						Le module de connexion personnalis&eacute;e a &eacute;t&eacute; d&eacute;sactiv&eacute; !
					
					</p>
					
				<?php
			}
			
			elseif(getEtat($donnees, "connexion", 1)=="non"){
				$etat="oui";
				
				Query("INSERT INTO connexions(id_site, date_modif) VALUES('$id_site', '".time()."')");
				
				?>
					
					<p>
						Le module de connexion personnalis&eacute;e a &eacute;t&eacute; activ&eacute; !<br /><br />
						
						Voici le code &agrave; inclure sur la page de votre choix :<br />
						<br />
						<code>
							&lt;a <?=HTTP_PUBLIC;?>modules/connexion/<?=$id_site;?>&gt;Connectez-vous !&lt;/a&gt;
						</code>
					
					</p>
					
				<?php
			}
			
			

	   ?>
			
			
			
	   <?php }
	   
	   elseif($module=="tchat"){
		
			if(getEtat($donnees, "tchat", 1)=="oui"){
				$etat="non";
				
				Query("DELETE FROM tchat WHERE id_site='$id_site'");
				Query("DELETE FROM tchat_messages WHERE id_site='$id_site'");
				Query("DELETE FROM bans_chat WHERE id_site='$id_site'");
				
				?>
					
					<p>
						Le module de tchat a &eacute;t&eacute; d&eacute;sactiv&eacute; !
					
					</p>
					
				<?php
			}
			
			elseif(getEtat($donnees, "tchat", 1)=="non"){
				$etat="oui";
				$cle_modo = substr(sha1($donnees['namespace']), 5);
				Query("INSERT INTO tchat(id_site, date_modif, cle_modo) VALUES('$id_site', '".time()."', '$cle_modo')");
				
				?>
					
					<p>
						Le module de tchat a &eacute;t&eacute; activ&eacute; !<br /><br />
						
						Voici le code &agrave; inclure sur la page de votre choix :<br />
						<br />
						<code>
							&lt;a <?=HTTP_PUBLIC;?>modules/tchat/<?=$id_site;?>&gt;Acc&eacute;der au tchat !&lt;/a&gt;
						</code><br /><br />
						
						
						Placer ce code sur la page ou se situe le tchat<br /><br />
						<code>
							[IF Modo = 1][WT:AdminTchat][/IF] [IF Modo != 1][WT:Tchat][/IF] 
						</code>
					</p>
					
				<?php
			}
			
			

	   ?>
			
			
			
	   <?php }
	   
	   elseif($module=="liensjours"){
		
			if(getEtat($donnees, "liensjours", 1)=="oui"){
				$etat="non";
				
				Query("DELETE FROM liensjours WHERE id_site='$id_site'");
				
				?>
					
					<p>
						Le module de lien du jour a &eacute;t&eacute; d&eacute;sactiv&eacute; !
					
					</p>
					
				<?php
			}
			
			elseif(getEtat($donnees, "liensjours", 1)=="non"){
				$etat="oui";
				
				Query("INSERT INTO liensjours(id_site, date_modif) VALUES('$id_site', '".time()."')");
				
				?>
					
					<p>
						Le module de lien du jour a &eacute;t&eacute; activ&eacute; !<br /><br />
						
						Voici le code &agrave; inclure sur la page de votre choix :<br />
						<br />
						<code>
							&lt;a <?=HTTP_PUBLIC;?>modules/liensjours/<?=$id_site;?>&gt;Acc&eacute;der ici au contenu du jour !&lt;/a&gt;
						</code>
					
					</p>
					
				<?php
			}
			
			

	   ?>
			
			
			
	   <?php }
	   
	   
	   
	   ?>
	   
	   <br /><br />
	   <a href="/website/gerer/<?=$id_site;?>">Retour</a>
   </div>
<?php 

Query("UPDATE sites SET service_$module='$etat' WHERE id_site='$id_site'");

require_once('templates/bas.php');


