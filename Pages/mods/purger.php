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


if(getEtat($donnees, $module, 1)=="non")
	Redirect("/website/gerer/$id_site");


if($module=="tchat"){

	Query("UPDATE tchat_messages SET etat='2' WHERE id_site='$id_site' and etat='1'");
	Redirect("/mods/stats/tchat/$id_site");


}

Redirect("/");