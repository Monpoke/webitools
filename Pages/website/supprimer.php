<?php 

$id_site = intval(getParam(1));

$id=intval(getParam(1));

if(is_modo()){
if(is_modo(true))
	$cond = "membres.rang < '".MODERATEUR."'";
else
	$cond = "membres.rang<='".ADMINISTRATEUR."'";
}
else
	$cond = "sites.id_membre='".get('id')."'";

$retour = Query("SELECT * FROM sites, membres WHERE sites.id_site='$id_site' and $cond and sites.id_membre=membres.id");
if(mysql_num_rows($retour)==1){
	Query("DELETE FROM sites WHERE id_site='$id_site' ");
	Query("DELETE FROM votes WHERE id_site='$id_site'");
	Query("DELETE FROM inscriptions WHERE id_site='$id_site'");
	Query("DELETE FROM connexions WHERE id_site='$id_site'");
	Query("DELETE FROM liensjours WHERE id_site='$id_site'");
	Query("DELETE FROM tchat_messages WHERE id_site='$id_site'");
	Query("DELETE FROM bans_chat WHERE id_site='$id_site'");
}
Redirect("/panel");