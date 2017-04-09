<?php
$id_site = intval(getParam(1));


$retour_site = Query("SELECT * FROM sites WHERE id_site='$id_site' and etat='2'");

if (mysql_num_rows($retour_site) != 1) {
    Redirect("/", 5);
    $titre = "Module de vote";
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

if ($donnees['service_vote'] == 'non') {
    Redirect("/", 5);
    $titre = "Module de vote";
    require('templates/haut_mod.php');
    ?>

    <div class="bloc_erreur">
        Le webmaster du dit-site n'a pas activ&eacute; ce module.<br /><br />

        <a href="/" onclick="javascript:history.go(-1)">Retour</a>
    </div>


    <?php
    require('templates/bas_mod.php');
    exit;
}


if (getParam(2) == "vote") {
    ?>
    <form action="http://www.webidev.com/fr/WebiVote" id="form" method="post" onload="this.submit()">
        <input type="hidden" name="NomSite" value="<?= $donnees['namespace']; ?>" />
        <noscript><input type="submit" value="Cliquez ici !" /></noscript>

    </form>	
    <script type="text/javascript">
    //<![CDATA[
        window.onload = function () {
            document.getElementById('form').submit();
        }
    //]]>
    </script>

    <?php
    exit;
}


if (isset($_POST['site'])) {

    if (empty($_POST['site']) or strlen($_POST['site']) < 3 or strlen($_POST['site']) > 255) {
        $erreur = true;
        $message .= "L'url est incorrect" . "<br />";
    } elseif ($_POST['site'] == $donnees['namespace']) {
        $erreur = true;
        $message .= "Vous ne pouvez voter pour votre propre site !" . "<br />";
    } else {
        $site = urlencode(Secure($_POST['site']));
        $retour_site = @file_get_contents('http://webidev.com/' . $site);

        if (!preg_match("#virtuel avec Webidev#", $retour_site)) {
            $erreur = true;
            $message .= "Le site est introuvable !" . "<br />";
        } else {
            $voteur = Secure($_POST['site']);

            $retour = Query("SELECT * FROM votes WHERE voteur='$voteur' and id_site='$id_site'");

            if (mysql_num_rows($retour) == 0)
                Query("INSERT INTO votes(id_site, voteur, date) VALUES('$id_site', '$voteur', '" . time() . "')");


            $msg_to = $donnees['namespace'];

            $msg_message = <<<EOF
Le webmaster du site $voteur a voté pour toi !

Enjoy =D

L'équipe WebiTools

Ps: message envoyé automatiquement ;)
EOF;

            $msg_titre = "Vote d'un webmaster";
            $envoi = true;

            require('envoi.php');

            Redirect("/modules/vote/$id_site/vote");
        }
    }
}


$titre = "Module de vote";
require('templates/haut_mod.php');
?>

<div class="bloc_confirm">
    <form action="/modules/vote/<?= $id_site; ?>" method="post">
<?php if (isset($erreur) && $erreur) { ?>
            <p class="erreur">
    <?= $message; ?>
            </p>
<?php } ?>
        <p>
            Vous vous appr&ecirc;tez &agrave; voter pour le site <b><?= $donnees['nom']; ?></b><br /><br />

            Pour que le webmaster du site s&acirc;che que vous avez vot&eacute; pour lui, entrez l'url de votre site :<br /><br />

            <label for="csite">Url : http://webidev.com/</label><input type="text" name="site" id="csite" /><br />
            <input type="submit" value="Voter !" name="voter" /> - <a href="<? if (!empty($_SERVER['HTTP_REFERER'])) {
    echo $_SERVER['HTTP_REFERER'];
} else { ?>http://webidev.com/<?= $donnees['namespace'];
} ?>">Retour</a><br /><br />
            <small><i><a href="/modules/vote/<?= $id_site; ?>/vote">Voter rapidement</a></i></small>
        </p> 
    </form>
</div>




<?php
require('templates/bas_mod.php');
