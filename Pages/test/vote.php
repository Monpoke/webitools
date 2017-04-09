<?php 

$retour = Query("SELECT * FROM votes");

$sites = array();

while($r=mysql_fetch_assoc($retour)){
if($r['id_site']==1)
continue;

if(!isset($sites[$r['id_site']]))
$sites[$r['id_site']]="";

$votes[$r['id_site']][] = $r;

}



foreach($sites as $k=>$v){

$sites[$k] = count($votes[$k]);

}

asort($sites);

print_r($sites);
