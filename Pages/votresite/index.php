<?php 

if(!is_membre()){
	Redirect("/membre/connexion");
}

$titre = "Votre publicité";
require_once('templates/haut.php');
?>
   <div id="content2">
	   <h3>Votre publicité ?</h3>

	   <p>
			Grâce au réseau de sites de WebiTools vous pouvez bénéficier d'un afflux de visiteurs supplémentaires.<br />
			Vous pouvez obtenir un lien vers votre site en échange d'un code Allopass, CellPass ou du moyen de votre choix. Ce code va directement créditer notre compte d'hébergement.<br />
			<br />
			
			Après cela, veillez à nous envoyer un email à l'adresse <em><a href="mailto:pub@webis.plumedor.fr">pub@webis.plumedor.fr</a></em> en nous envoyant clairement 
		</p>
			<ul>
				<li>votre email  (l'email que vous avez précisé sur le site de notre hébergeur)</li>
				<li>votre site internet</li>
			</ul>
		<p>
			<br />
			<br />
			<a href="<?php echo AIDE_LIEN;?>" target="_blank"><strong>&raquo; Accès vers la plateforme de notre hébergeur</strong></a>
			<br /><br />
			Après notre échange par email, votre site sera affiché pour une <em>durée d'un mois</em> sur la plateforme :).<br /><br />
			
			Ce geste est <em>très important pour nous</em> pour maintenir le serveur de WebiTools en vie.<br /><br />
	   </p>
	   
	   
	   <h2>Merci beaucoup, #Monpoke</h2>
		
		
   </div>
<?php 
require_once('templates/bas.php');


