<?php
if (getParam(3) == "integre") {

    Query("UPDATE connexions SET type='1' WHERE id_site='$id_site'");
    Redirect("/mods/perso/connexion/$id_site");
}



$conf = mysql_fetch_array(Query("SELECT * FROM connexions WHERE id_site='$id_site'"));


if (isset($_POST['charge_exemple'])) {

    if (!empty($_POST['exemple']) && preg_match("#^site_([0-9])$#", $_POST['exemple'])) {

        $id_site2 = intval(substr($_POST['exemple'], strlen('site_')));

        $retour = Query("SELECT * FROM sites WHERE service_connexion='oui' && id_site='$id_site2'");

        if (mysql_num_rows($retour) == 1) {

            $donnees = mysql_fetch_array($retour);
            $retour = Query("SELECT * FROM connexions WHERE id_site='{$donnees['id_site']}'");

            $champs = array();
            $valeurs = array();

            $donnees_exemple = mysql_fetch_array($retour);

            $prendre = false;
            foreach ($donnees_exemple as $nom => $val) {
                if ($prendre) {
                    $champs[] = $nom;
                    $valeurs[] = Secure($val);
                }

                if ($prendre)
                    $prendre = false;
                else
                    $prendre = true;
            }

            $valeurs[0] = $id_site;


            // sql
            $sql = "INSERT INTO connexions(";

            foreach ($champs as $nom) {
                $sql.= $nom . ", ";
            }

            $sql = substr($sql, 0, -2);

            $sql.=") VALUES (";

            foreach ($valeurs as $nom) {
                $sql.= "'$nom', ";
            }

            $sql = substr($sql, 0, -2);

            $sql .= ")";
            // on supprime le design en cours
            Query("DELETE FROM connexions WHERE id_site='$id_site'");
            Query($sql);

            Redirect("/mods/perso/connexion/$id_site/modok");
        }
    }
} elseif (isset($_POST['formu'])) {

    $erreur = false;
    $message = "";

    // verifs textes
    if (!isset($_POST['pseudo']) or strlen($_POST['pseudo']) > 30) {
        $erreur = true;
        $message .= "'Pseudo' : 30 caract&egrave;res maxi." . "<br />";
    } else {
        $texte_pseudo = Secure($_POST['pseudo']);
    }


    if (!isset($_POST['inscrire']) or strlen($_POST['inscrire']) > 30) {
        $erreur = true;
        $message .= "'Bouton Connexion' : 30 caract&egrave;res maxi." . "<br />";
    } else {
        $texte_inscrire = Secure($_POST['inscrire']);
    }

    if (!empty($_POST['test_pseudo'])) {

        if (empty($_POST['test_pass'])) {
            $erreur = true;
            $message .= "'Le mot de passe du compte testeur doit être fourni !" . "<br />";
        } else {
            $test_pseudo = Secure($_POST['test_pseudo']);
            $test_pass = Secure($_POST['test_pass']);
        }
    } else {
        $test_pseudo = "";
        $test_pass = "";
    }




    // VERIFS CSS
    $sql = "";
    $champs_couleur = array('1', '3', '6', '14', '25', '20', '26', '21', '27', '29', '32', '33', '34', '35');
    $champs_nombre = array('2', '4', '5', '7', '8', '10', '28', '16', '15', '18',);
    $url_images = array('22', '23', 'header');
    $champs_alignement = array('19');
    $champs_id_page = array('30', '31');

    foreach ($_POST as $nom => $valeur) {

        if (preg_match("#champ_#", $nom)) {

            $id = explode('_', $nom);
            $id = $id[1];


            if (in_array($id, $champs_couleur)) {

                if (!isColor($valeur)) {
                    $erreur = true;
                    $message .= "Une des couleurs est invalide !" . "<br />";
                }
            } elseif (in_array($id, $champs_nombre)) {

                if ($valeur < 0 or $valeur > 999) {
                    $erreur = true;
                    $message .= "Une des valeurs num&eacute;rique est invalide !" . "<br />";
                }
            } elseif (in_array($id, $champs_id_page)) {

                if (is_nan($valeur)) {
                    $erreur = true;
                    $message .= "Une des valeurs Id d'une page est invalide !" . "<br />";
                }
            } elseif (in_array($id, $url_images)) {

                if (!empty($valeur) && !isBildberg($valeur)) {
                    $erreur = true;
                    $message .= "Une des images est invalide !" . "<br />";
                } elseif (!empty($valeur)) {
                    $_POST[$nom] = preg_replace('##', '', $valeur);
                }
            } elseif (in_array($id, $champs_alignement)) {

                if (empty($valeur) or $valeur != "left" && $valeur != "right" && $valeur != "center") {
                    $erreur = true;
                    $message .= "L'alignement est invalide !" . "<br />";
                }
            } else {

                exit("$nom a un probleme !");
            }
        }
    }


    if (!$erreur) {

        $sql = "";

        foreach ($_POST as $nom => $valeur) {

            if (preg_match("#champ_#", $nom)) {

                $nom = substr($nom, 6);

                //on associe

                $sql.="champ_$nom='" . Secure($valeur) . "', ";
            }
        }



        Query("UPDATE connexions SET texte_pseudo='$texte_pseudo', $sql texte_btn_inscrire='$texte_inscrire', test_pseudo='$test_pseudo', test_pass='$test_pass' WHERE id_site='$id_site'");
        Redirect("/mods/perso/connexion/$id_site");
    }
}
?>

<form action="/mods/perso/connexion/<?= $id_site ?>" method="post">
<?php if (isset($erreur) && $erreur) { ?>
        <p class="erreur">
    <?= $message; ?>
        </p>
<?php } elseif (getParam(3) == "modok") { ?>
        <p class="confirm">
            Le mod&egrave;le a &eacute;t&eacute; charg&eacute; avec succ&egrave;s !
        </p>
<?php } ?>
    <br />
    <label for="champ_31"><b>ID</b> de la page o&ugrave; greffer le formulaire :</label> <input size="11" maxlength="9" type="text" name="champ_31" id="champ_31" value='<?= input_value($conf['champ_31'], 'champ_31'); ?>' />*<br />
    Sur cette page, incluez la balise <i>[Mod Connect]</i> ou le mod <i>[Mod ConnectSeul]</i>.<br />


    <h2>Les textes</h2>

    <label for="cpseudo">Pseudo :</label> <input type="text" name="pseudo" id="cpseudo" value='<?= input_value($conf['texte_pseudo'], 'pseudo'); ?>' /><br />
    <label for="cinscrire">Connecter :</label> <input type="text" name="inscrire" id="cinscrire" value="<?= input_value($conf['texte_btn_inscrire'], 'inscrire'); ?>" /><br />
    <br />

    <input type="submit" value="Sauvegarder" /> <a target="_blank" href="/modules/connexionv2/<?= $id_site ?>/preview"><input type="button" value="Aper&ccedil;u" /></a>
    <br /><br />
    <hr />
    <br />
    <h2>Compte de test</h2>
    <i>Cette nouvelle option vous permet en un lien, de connecter le joueur sur un compte pour tester le jeu.<br />
        Ainsi, le joueur n'est plus obligé de connaitre le mot de passe et par conséquent, il ne peut plus le changer :)</i>
    <br /><br />
    <label for="ctest_pseudo">Pseudo :</label> <input type="text" name="test_pseudo" id="ctest_pseudo" value='<?= input_value($conf['test_pseudo'], 'test_pseudo'); ?>' /><br />
    <label for="ctest_pass">Mot de passe :</label> <input type="text" name="test_pass" id="ctest_pass" value="<?= input_value($conf['test_pass'], 'test_pass'); ?>" /><br />
    <br /><br />

    <b>Connexion directe sur ce compte : </b> <br />
    &lt;a <?= HTTP_PUBLIC; ?>modules/connexionv2/<?= $id_site; ?>?test&gt;Tester le jeu !&lt;/a&gt;
    <br /><br />
    <hr />

    <h2>Les couleurs</h2>

    <h3>Fond de page</h3>
    Design int&eacute;gr&eacute;<br /><br />

    <h3>Formulaire</h3>

    <label for="champ_14">Couleur de fond :</label> <input size="6" maxlength="6" type="text" class="color" name="champ_14" id="champ_14" value='<?= input_value($conf['champ_14'], 'champ_14'); ?>' />*<br />
    <label for="champ_25">Couleur de fond sur focus :</label> <input size="6" maxlength="6" type="text" class="color" name="champ_25" id="champ_25" value='<?= input_value($conf['champ_25'], 'champ_25'); ?>' />*<br />
    <label for="champ_29">Couleur du texte :</label> <input size="6" maxlength="6" type="text" class="color" name="champ_29" id="champ_29" value='<?= input_value($conf['champ_29'], 'champ_29'); ?>' />*<br />

    <label for="champ_28">Hauteur:</label> <input size="2" maxlength="2" type="text" name="champ_28" id="champ_28" value='<?= input_value($conf['champ_28'], 'champ_28'); ?>' /> <br />
    <label for="champ_10">Arrondissement de la bordure :</label> <input size="2" maxlength="2" type="text" name="champ_10" id="champ_10" value='<?= input_value($conf['champ_10'], 'champ_10'); ?>' /> en pixels<br />
    <br />
    <label for="champ_15">Arrondissement du bouton :</label> <input size="2" maxlength="2" type="text" name="champ_15" id="champ_15" value='<?= input_value($conf['champ_15'], 'champ_15'); ?>' /> en pixels<br />
    <label for="champ_16">Largeur du bouton :</label> <input size="2" maxlength="2" type="text" name="champ_16" id="champ_16" value='<?= input_value($conf['champ_16'], 'champ_16'); ?>' /> en pixels<br />
    <label for="champ_18">Hauteur du bouton :</label> <input size="2" maxlength="2" type="text" name="champ_18" id="champ_18" value='<?= input_value($conf['champ_18'], 'champ_18'); ?>' /> en pixels<br />
    <label for="champ_32">Couleur de fond :</label> <input size="6" maxlength="6" type="text" class="color" name="champ_32" id="champ_32" value='<?= input_value($conf['champ_32'], 'champ_32'); ?>' />*<br />
    <label for="champ_33">Couleur du texte :</label> <input size="6" maxlength="6" type="text" class="color" name="champ_33" id="champ_33" value='<?= input_value($conf['champ_33'], 'champ_33'); ?>' />*<br />
    <label for="champ_34">Couleur de fond hover :</label> <input size="6" maxlength="6" type="text" class="color" name="champ_34" id="champ_34" value='<?= input_value($conf['champ_34'], 'champ_34'); ?>' />*<br />
    <label for="champ_35">Couleur du texte hover :</label> <input size="6" maxlength="6" type="text" class="color" name="champ_35" id="champ_35" value='<?= input_value($conf['champ_35'], 'champ_35'); ?>' />*<br />
    <br />

    <label for="champ_19">Position du texte :</label> 
    <select name="champ_19" id="champ_19">
        <option value="left">Gauche</option>
        <option value="center">Centr&eacute;</option>
        <option value="right">Droite</option>

    </select>

    <br /><br />
    <label for="champ_20">Couleur du message d'erreur :</label> <input size="6" maxlength="6" type="text" class="color" name="champ_20" id="champ_20" value='<?= input_value($conf['champ_20'], 'champ_20'); ?>' />*<br />
    <label for="champ_26">Couleur du message de confirmation :</label> <input size="6" maxlength="6" type="text" class="color" name="champ_26" id="champ_26" value='<?= input_value($conf['champ_26'], 'champ_26'); ?>' />*<br />


    <br />

    <br />




    <input type="hidden" name="formu" value="true" />
    <br />
    <input type="submit" value="Sauvegarder" /> <a target="_blank" name="apercu" href="/modules/connexionv2/<?= $id_site ?>/preview"><input type="button" value="Aper&ccedil;u" /></a>
    <br />



</form>



<b><u>Charger un site exemple</u></b><br />
Exemple &agrave; charger :

<form action="/mods/perso/connexion/<?= $id_site ?>" method="post">
    <select name="exemple">
        <option value="">--------------</option>
<?php
$retour = Query("SELECT * FROM sites WHERE type='demo' and service_connexion='oui'");

while ($do = mysql_fetch_array($retour)) {
    ?>
            <option value="site_<?= $do['id_site']; ?>"><?= $do['nom']; ?></option>

    <?php }
?>
    </select>
    <br />
    <input type="hidden" name="charge_exemple" value="true" />
    <input type="submit" value="Charger" onclick="return(confirm('Cela &eacute;crasera toutes vos donn&eacute;es !'));
                return false;" />
</form>
<br />

<b><u>Rappel :</u></b><br />
<br />
<code>
    &lt;a <?= HTTP_PUBLIC; ?>modules/connexionv2/<?= $id_site; ?>&gt;Connectez vous !&lt;/a&gt;
</code><br /><br />


<code>
    &lt;a <?= HTTP_PUBLIC; ?>modules/deco/<?= $id_site; ?>&gt;Déconnectez vous en cliquant ici&lt;/a&gt;
</code><br /><br />


<a href="/mods/perso/connexion/<?= $id_site ?>/integre">-> Passer en mode page externe</a>





