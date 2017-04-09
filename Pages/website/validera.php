<?php 

$com="";
$id=get('id');

if(is_modo()){
	
	$id_2 = intval(getParam(2));
	
	if(is_modo(true))
		$cond = "membres.rang < '".MODERATEUR."'";
	else
		$cond = "membres.rang<='".ADMINISTRATEUR."'";
		
	
	$retour=Query("SELECT * FROM membres WHERE id='$id_2' and $cond");
	
	if(mysql_num_rows($retour)==1){
		$com="/$id_2";
		$id=$id_2;
	
	}
	

}

$id_site = intval(getParam(1));
$retour_site = Query("SELECT * FROM sites WHERE id_site='$id_site' and id_membre='$id' and etat='1'");

if(mysql_num_rows($retour_site)!=1)
	Redirect("/panel");

	
$donnees=mysql_fetch_array($retour_site);


Query("UPDATE sites SET etat='2' WHERE id_site='$id_site'");

if(empty($com))
	Redirect("/panel");
else
	Redirect("/admin/membre_voir/$id");
	