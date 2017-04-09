<?php

if(is_membre())
	$_SESSION['donnees_membre'] = mysql_fetch_array(Query("SELECT * FROM membres WHERE id='{$_SESSION['donnees_membre']['id']}'"));



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" media="screen" type="text/css" title="style" href="/style.css" />
<script type="text/javascript" src="/jscolor/jscolor.js"></script>

		<script src="/js/jquery-1.6.1.min.js" type="text/javascript" charset="utf-8"></script>
		<link rel="stylesheet" href="/css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
		<script src="/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>

<title>
	<?php 
	if(!isset($titre)){
		echo "WebiTools";
	} else {
		echo $titre." - WebiTools";
	}
	
	
	?>
</title> 


<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-34275806-1']);
  _gaq.push(['_setLocalRemoteServerMode']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>
<body>
 <div id="holder">																																																																																																																																																																																																																																																																																																																				

<!-- HEADER -->
<div id="header"> <a href="/"> <img src="/images/logo_gif.gif" alt="logo" width="273" height="103"/> </a> </div>
<!-- END HEADER -->
<div id="shadow">
  <!-- MENU -->
  <ul id="menu">
    <li> <a href="/">Accueil</a> </li>
    <li> | </li>
	<?php 
	if(!is_membre()){?>
    <li> <a href="/services">Services</a> </li>
    <li> | </li>
	<li> <a href="/contact">Contact</a> </li>
    <li> | </li>
	<?php } else {?>
	<li> <a href="/panel">Panel</a> </li>
    <li> | </li>
	<li> <a href="/compte">Mon compte</a> </li>
    <li> | </li>
	<?php }?>
    <li> <a href="/annuaire">Annuaire</a> </li>
    <li> | </li>
	<?php 
	if(!is_membre()){?>
		<li> <a href="/membre/connexion">Connexion</a> </li>
	<?php } else {?>
		<li> <a href="/membre/deconnexion">D&eacute;connexion</a> </li>
	<?php }?>
  </ul>
  <div class="clear"></div>
  
  
  <div id="edito">
		<?php 
		if(!is_membre()){?>
			<h2>Bienvenue !</h2>
			<p>
				WebiTools est un site d'entraide exclusivement r&eacute;serv&eacute; aux Webinautes.<br />
				De nombreux modules sont disponibles pour am&eacute;liorer votre site. D&eacute;couvrez les vite en vous inscrivant !
			</p>
			<a href="/membre/inscription" id="button_edito">Inscription</a>
		<?php } else {?>
			<h2>Bienvenue <?=get('pseudo');?> !</h2>
			<p>
				WebiTools est un site d'entraide exclusivement r&eacute;serv&eacute; aux Webinautes.<br />
				
				WebiTools a été relancé le 1<sup>er</sup> mars 2014 après une interruption de service en septembre dernier. Cependant, visant à d'autres projets, je ne m'occuperai plus de la maintenance et de l'ajout de nouveautés. Pour toutes questions, adressez-vous sur les forums de <a href="http://webidev.com/?Parrain=monpoke" style="color:black;">Webidev</a>.<br />
				Bonne utilisation, Monpoke.
			</p>
			
			<?php if(is_modo()){?>
				<a href="/admin" id="button_edito">Administration</a>
			<?php } ?>
		<?php }?>
		
   </div>
  
  
  <div id="toal"> </div>

  

  <div id="content">
  