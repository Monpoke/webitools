<?php 
$titre="Accueil";
require_once('templates/haut.php');
?>
	
	<?php 
	if(getParam(0)=='deok'){
	?>
		<p class="confirm">
			D&eacute;connexion effectu&eacute;e !
		</p>
	<?php 
	}?>
    <!-- ABOUT ME -->
    <div class="div">
      <h1>A propos</h1>
      <p> <em>Site r&eacute;serv&eacute; aux Webinautes</em></p>
      <p>
		Lanc&eacute; en ao&ucirc;t 2012, WebiTools est un site permettant aux Webinautes de personnaliser leur site au maximum.
		<br />
	  </p>
    </div>
    <!-- END ABOUT ME -->
    <!-- NEWS -->
    <div id="vertical_barr div">
      <h1>News</h1>
      <p style="color: red"><em>01/03/2014</em><strong>Remise en route du service</strong></p>
      <p style="color: red"><em>25/08/2012</em><strong>Implementation du lien du jour</strong></p>
      <p style="color: red"><em>07/08/2012</em><strong>Implementation de la connexion personnalis&eacute;e</strong></p>
      <p style="color: red"><em>07/08/2012</em><strong>Implementation de l'inscription personnalis&eacute;e</strong></p>
      
      </div>
    <!-- END NEWS -->
    <!-- SERVICES -->
    <div class="div">
      <h1>Services</h1>
      <p>WebiTools propose des outils exclusifs pour <a href="http://webidev.com/?Parrain=monpoke">Webidev</a>.</p>
      <ul>
        <li><a href="/">Inscription personnalis&eacute;e</a></li>
        <li><a href="/">Connexion personnalis&eacute;e</a></li>
        <li><a href="/">Vote entre Webmasters</a></li>
        <li><a href="/">Lien du jour</a></li>
      </ul>
      <a href="#" class="content_button">D&eacute;couvrez les</a>
     </div>
  
<?php 
require_once('templates/bas.php');


