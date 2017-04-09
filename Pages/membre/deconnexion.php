<?php 
session_unset();

session_destroy();

$param=getParam(1);

if($param=="securite")
	Redirect("/membre/connexion/secu");
else
	Redirect("/index/deok");