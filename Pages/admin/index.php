<?php 


$titre = "Panel admin";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Panel admin</h3>
	   
		Bienvenue <?=get('pseudo');?> !<br /><br />
		
		<ul>
		
			<li><a href="/admin/membresv">Consulter la liste des membres</a></li>
			<li><a href="/admin/sites">Consulter la liste des sites</a></li>
			<li><a href="/admin/recherche">Recherche</a></li>
			<li><a href="/admin/tchat">Monitoring Tchat</a></li>
			
			<?php if(is_admin()){?>
				<li><a href="/admin/campagnes">G&eacute;rer les campagnes</a></li>
			<?php } ?>
		</ul>
	   
   </div>
<?php 
require_once('templates/bas.php');


