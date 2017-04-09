<?php
$id_site = intval(getParam(1));



$retour_site = Query("SELECT * FROM sites, membres WHERE sites.id_site='$id_site' and sites.etat='2' and membres.id = sites.id_membre");

if (mysql_num_rows($retour_site) != 1) {
    Redirect("/", 5);
    $titre = "Module de connexion";
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
$conf = mysql_fetch_array(Query("SELECT * FROM connexions WHERE id_site='$id_site'"));


if ($donnees['service_connexion'] == 'non') {
    Redirect("/", 5);
    $titre = "Module d'connexion";
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
if ($conf['type'] == 1) {
    Redirect("/modules/connexion/{$id_site}{$com}");
}
$com = "";
if (getParam(2) == "preview" && $donnees['id_membre'] == get('id')) {
    $com = "/preview";
}

// VERIF SITES FERMES OU connexions FERMES

$com = "";
if (getParam(2) == "preview" && ($donnees['id_membre'] == get('id') or is_modo())) {
    $com = "/preview";
}

if ($conf['type'] == 1) {
    Redirect("/modules/connexion/{$id_site}{$com}");
}


if (isset($_GET['test']) && !empty($conf['test_pseudo']) && !empty($conf['test_pass'])) {

    $val1 = array(
        'Pseudo' => $conf['test_pseudo'],
        'Password' => $conf['test_pass'],
    );

    $_SESSION['auth_' . $id_site]['Pseudo'] = Secure($val1['Pseudo']);
    $_SESSION['auth_' . $id_site]['Password'] = base64_encode($val1['Password']);
    ?>

    <form id="form" action='http://www.webidev.com/<?= $donnees['namespace'] . '/MConnect'; ?>' method="post">
    <?php
    foreach ($val1 as $nom => $val) {
        ?>
            <input type="hidden" name="<?= $nom; ?>" value="<?= $val; ?>" />
        <?php }
    ?>

        <noscript><input type="submit" value="Cliquez ici !" /></noscript>

    </form>	

    <script type="text/javascript">
        //<![CDATA[
        window.onload = function() {
            document.getElementById('form').submit();
        }
        //]]>
    </script>


    <?php
    exit;
}
// VERIF SITES FERMES OU connexionS FERMES
$ferme = false;



/* CACHE DU SYSTEME */
$nom_en_cache = $id_site;
$extension_cache = "cac";
$suffixe_cache = "cov2";


$cache = new Cache();

if ($cache->setPage($nom_en_cache, 'private', $extension_cache, $suffixe_cache)) {

    if ($cache->checkCache()) {

        $retour = $cache->getCache('private');
    } else {

        $retour = @file_get_contents('http://www.webidev.com/' . $donnees['namespace'] . "/ViewPage?Id=" . $conf['champ_31']);

        $cache->updateCache($retour);

        if (is_developper())
            echo "<b>Le fichier cache n'existait pas. Cr&eacute;ation effectu&eacute;e<br /></b>";
    }
}



// CONFIGURATIONS


extract($conf);
// on configure les textes ici
if (empty($conf['texte_pseudo']) or $conf['texte_pseudo'] == "@1") {
    $texte_pseudo = "Pseudo";
} else {
    $texte_pseudo = htmlentities($conf['texte_pseudo']);
}


if (empty($conf['texte_btn_inscrire']) or $conf['texte_btn_inscrire'] == "@1") {
    $texte_inscrire = "S'inscrire";
} else {
    $texte_inscrire = $conf['texte_btn_inscrire'];
}



ob_start();
$style = "

	.perso {
			
		color: #{$champ_29}; /* 10 */
		border-radius: {$champ_10}px; /* 10 */
		height: {$champ_28}px; /* 10 (bis) */
		background: #{$champ_14}; /* 14 */
		border: solid 1px black; /* 12 */
		text-align: center; 
		margin-bottom: 4px;
	}
	
	.perso:focus {
		
		background: #{$champ_25} ; /* 14 (2) */
	}
	
	.submit  {
		border-radius: {$champ_15}px; /* 15  */
		
		width: {$champ_16}%; /* 16 */
		
		margin-top: 12px;/* 17 (bis) */
		
		background-color: #{$champ_32};
		
		color: #{$champ_33};
		
		
		height: {$champ_18}px; /* 18 */
		vertical-align: middle; 
		text-align: center;
	}
	
	.submit:hover  {

		background-color: #{$champ_34};
		
		color: #{$champ_35};
		
	}
	
	
	.erreur {
	
		color: #{$champ_20}; /* 20 */
		font-weight: bold;
		
		text-align: center;
	}
	
	#pub {
			position: fixed;
			bottom:0;
			left: 0;
			padding: 5px;
			-webkit-border-top-right-radius: 5px;
			-moz-border-radius-topright: 5px;
			border-top-right-radius: 5px;
			background: #ffffff;
			color: #000000;
		
		}
		
</style>
";

if (isset($_POST['Pseudo'])) {

    $erreur = false;
    $message = "";


    if (empty($_POST['Pseudo']) or strlen($_POST['Pseudo']) > 20) {
        $erreur = true;
        $message .= "Le pseudo ne peut-&ecirc;tre vide !" . "<br />";
    }

    if (empty($_POST['Password']) or strlen($_POST['Password']) > 20 or strlen($_POST['Password']) < 5) {
        $erreur = true;
        $message .= "Le mot de passe doit comporter 5 caract&egrave;res minimum !" . "<br />";
    }


    // Pas d'erreur
    if ($erreur == false) {


        if ($com != "/preview") {
            // Submit those variables to the server
            $post_data = array(
                'Connecter' => "Connecter"
            );

            foreach ($_POST as $valeur => $val) {

                $post_data[$valeur] = $val;
            }

            // Send a request to example.com 
            $result = post_request('http://www.webidev.com/' . $donnees['namespace'] . '/MConnect', $post_data);

            if ($result['status'] == 'ok') {
                // print the result of the whole request:
                if (!preg_match("#Le pseudo ou le mot de passe n'est pas correct.#", $result['content'])) {

                    $_SESSION['auth_' . $id_site]['Pseudo'] = Secure($_POST['Pseudo']);
                    $_SESSION['auth_' . $id_site]['Password'] = base64_encode($_POST['Password']);
                    ?>
                    <form id="form" action='http://www.webidev.com/<?= $donnees['namespace'] . '/MConnect'; ?>' method="post">
                    <?php
                    foreach ($_POST as $nom => $val) {
                        ?>
                            <input type="hidden" name="<?= $nom; ?>" value="<?= $val; ?>" />
                        <?php }
                    ?>

                        <noscript><input type="submit" value="Cliquez ici !" /></noscript>

                    </form>	
                    <script type="text/javascript">
                        //<![CDATA[
                        window.onload = function() {
                            document.getElementById('form').submit();
                        }
                        //]]>
                    </script>


                        <?php
                        exit;
                    } else {
                        $erreur = true;
                        $message = "Vos identifiants sont incorrects !";
                    }
                } else {
                    $erreur_fatale = true;
                    $message = "Une erreur est survenue ! <b>Code B86</b><br /> Le probl&egrave;me a &eacute;t&eacute; signal&eacute; ! <br />";

                    if (is_developper())
                        $message .= $result['error'];


                    ob_start();
                    print_r($_SERVER);
                    print_r($_POST);
                    $contenu = ob_get_clean();
                }
            } else {
                exit("Puis redirection vers votre site :)");
            }
        }
    }





    $namespace = $donnees['namespace'] . $com;



    $retour = preg_replace("#name='Pseudo' size='14'#Usi", "name='Pseudo' ' size='14' value='$texte_pseudo' class='perso' ", $retour);
    $retour = preg_replace("#this.value=='Pseudo'#Usi", "this.value=='$texte_pseudo'", $retour);
    $retour = preg_replace("#name='Password'#Usi", "name='Password' class='perso' ", $retour);
    $retour = preg_replace("#value='Connecter'#Usi", "value='$texte_inscrire' class='submit' ", $retour);

    if (!isset($erreur) or !$erreur)
        $retour = preg_replace("#<form action=\"(.+)MConnect\"#Usi", "<form action=\"/modules/connexionv2/$id_site\"", $retour);
    else
        $retour = preg_replace("#<form action=\"(.+)MConnect\"#Usi", "<p class=\"erreur\">$message</p><form action=\"/modules/connexionv2/$id_site\"", $retour);

    $pub = <<<EOF
_gaq.push(['_setAccount', 'UA-34275806-2']);
 _gaq.push(['_setDomainName', 'plumedor.fr']);
EOF;

    $retour = preg_replace("#_gaq.push(['_setAccount', 'UA\-22532783\-1']);#", $pub, $retour);

// supprime pub

    $retour = preg_replace("#</style>#Usi", $style, $retour);



    echo $retour;


    if ($donnees['pub'] == "oui" && PUB_PLUME) {
        ?>
    <div id="pub"><a href="http://plumedor.fr">Pub : <b>Plume d'Or</b> ouvre en <b>b&ecirc;ta-test</b></a></div>

<?php } elseif ($donnees['pub'] == "oui") { ?>
    <div id="pub"><?= PUBLICITE; ?></div>


<?php } elseif ($donnees['type'] == 'demo') { ?>
    <div id="pub">
        Version de d&eacute;monstration<br />
        Les images appartiennent &agrave; leur auteurs respectifs.
    </div>
<?php } ?>
</div>

<script type="text/javascript">

    $j(function() {

        var couleur = $j(".trcorpspage").css('color');
        var bgimg = $j(".trcorpspage").css('background-image');
        var bgcolor = $j(".trcorpspage").css('background-color');



        $j("#pub").css('color', couleur);
        $j("#pub").css('background-color', bgcolor);
        $j("#pub").css('background-image', bgimg);





    });

</script>

<?php
$retour = preg_replace("#</style>#Usi", $style, $retour);





