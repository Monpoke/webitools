<?php 

$retour = @file_get_contents("http://www.webidev.com/monpoke/ViewPage?Id=902186");




// traitement

$mod = "inscription";

$mods = array('tchat', 'inscription', 'connexion');


$retour = preg_replace("#&lt;WT:(^tchat)&gt;(.+)&lt;/WT:(^tchat)&gt;(<br />)?#Usi", "", $retour);





echo $retour;