<?php
$id_site = intval(getParam(1));



$retour_site = Query("SELECT * FROM sites, membres WHERE sites.id_site='$id_site' and sites.etat='2' and membres.id = sites.id_membre");

if (mysql_num_rows($retour_site) != 1) {
    Redirect("/", 5);
    $titre = "Module de déconnexion";
    require('templates/haut_mod.php');
    ?>

    <div class="bloc_erreur">
        Le site en question n'existe pas !<br /><br />

        <a href="/" onclick="javascript:history.go(-1)">Retour</a>
    </div>


    <?php
    require('templates/bas_mod.php');
    exit;
}

$donnees = mysql_fetch_array($retour_site);

if(isset($_SESSION['auth_'.$id_site])){
    unset($_SESSION['auth_'.$id_site]);
}

Redirect('http://www.webidev.com/'. $donnees['namespace'] . '/MDeco');