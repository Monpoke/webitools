<?php 

if(getParam(1)!="SuperCron789")
	exit(201 . "!");

echo "liens du jour<br />";
	
$retour = Query("SELECT * FROM liensjours");

while($do=mysql_fetch_array($retour)){

	$cours = $do['en_cours']+1;
	
	if($cours>7)
		$cours=1;
		
	Query("UPDATE liensjours SET en_cours='$cours' WHERE id_site='{$do['id_site']}'");

}

echo "Complet<br />";

echo "Update caches<br />";
// on supprime touts les caches

$cache = new Cache();

$cache->freeCache();

Query("UPDATE sites SET quota_cache='10'");

echo "Complet !";