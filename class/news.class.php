<?php
/* 
  ---- Système de news par Pegian ----
  
  - Utilisation : 
  $news = new news();
  $news->Connexion('host','user','pass','db');
  
  - Pour ajouter une news :
  Créer un formulaire puis une fois valider, utiliser :
  
  $news->AddNews($time,$title,$poster,$text);
  $time = Date + Heure
  $title = Le Titre
  $poster = L'Auteur
  $text = Le Texte de la News
  
  - Pour supprimer des news
  Créez un formulaire puis une fois valider, utiliser :
  
  $news->DelNews($id);
  $id = Variable contenant les IDs des news à supprimer. Le séparateur est un espace.
  
  - Pour éditer une news
  Créez un formulaire puis une fois valider, utiliser :
  
  $news->EditNews($id,$title,$poster,$text);
  $id = ID de la news à modifier.
  $title = Nouveau Titre
  $poster = Nouvel Auteur
  $text = Nouveau Texte
  
  - Pour afficher les news
  $news->SelectNews($nb,$sort,$order);
  $nb = Nombre de news à afficher par page
  $sort = Trier par rapport à $sort
  $order = ASC ou DESC (croissant ou décroissant)

  ------------------------------------
*/

class news {

  function Connexion($host,$user,$pass,$db) {
  
    $mysql = mysql_connect($host, $user,$pass) or die('Erreur SQL : '.mysql_error());
	mysql_select_db($db) or die('Erreur SQL : '.mysql_error());
	
  }
  
  function SelectNews($nb,$sort,$order) {
  
    if (!isset($_GET['num'])) { $_GET['num'] = 0; }
    
	$sql = 'SELECT * FROM `news`';
	$req = mysql_query($sql) or die('<center><div class="news-no">Erreur SQL : '.$sql.'<br />'.mysql_error().'</div></center>');
	$max = mysql_num_rows($req);
	
	if (!$max) { echo 'Aucune information n\'est disponible...'; }
	else {
	
      $sql = 'SELECT * FROM `news` ORDER BY '.$sort.' '.$order.' LIMIT '.$_GET['num'].','.$nb;
	  $req = mysql_query($sql) or die('<center><div class="news-no">Erreur SQL : '.$sql.'<br />'.mysql_error().'</div></center>');
	  echo '<span class="gras">Pages : '.Pagination($max,$nb,$_GET['num'],'3').'</span><br /><br />';
	  while ($res = mysql_fetch_object($req)) {
	    echo '<div class="news-bloc">';
	    echo '<div class="news-title"><img src="./themes/images/arrow-right.gif" alt="" />&nbsp;'.$res->title.'</div>'."\n";
	    echo '<div class="news-content">'.$res->text.'</div>'."\n";
	    echo '<div class="news-footer">Par '.$res->poster.' le '.ConvertTime($res->time,'fr').'</div>'."\n";
	    echo '</div>';
	    echo '<br />';
	  }
	  mysql_free_result($req);
	}
  }
  
  function AddNews($time,$title,$poster,$text) {
  
    if (empty($title)) { echo  '<center><div class="news-no">Le titre est vide !</div></center>'; }
	elseif (empty($text)) { echo  '<center><div class="news-no">Le texte est vide !</div></center>'; }
	else {
	  
	  $title = strClean($title);
	  $text = strClean($text);
  
      $sql = 'INSERT INTO `news` (`id`, `time`, `title`, `poster`, `text`) VALUES("", "'.$time.'", "'.$title.'", "'.$poster.'", "'.$text.'")';
	  mysql_query($sql) or die('<center><div class="news-no">Erreur SQL : '.$sql.'<br />'.mysql_error().'</div></center>');
	
	  echo  '<center><div class="news-ok">La news n°'.mysql_insert_id().' a bien été ajoutée.</div></center>';
	  
	}
	
  }
  
  function DelNews($id) {
    
	$array = explode('&nbsp;',$id);
	
	if (!$id) { echo  '<center><div class="news-no">Aucune sélection !</div></center>'; }
	else {
	  foreach($array as $val) {
	    $sql = 'DELETE FROM `news` WHERE id = "'.$val.'"';
	    mysql_query($sql) or die('<div class="news-no">Erreur SQL : '.$sql.'<br />'.mysql_error().'</div>');
	  }
	
	  if (count($array) > 2) { echo  '<center><div class="news-ok">'.(count($array)-1).' news ont été supprimées.</div></center>'; }
	  else { echo  '<center><div class="news-ok">La news n°'.$id.' a bien été supprimée.</div></center>'; }
	  
	}
	
  }
  
  function EditNews($id,$title,$poster,$text) {
  
    if (empty($title)) { echo  '<center><div class="news-no">Le titre est vide !</div></center>'; }
	elseif (empty($text)) { echo  '<center><div class="news-no">Le texte est vide !</div></center>'; }
	else {
	  
	  $title = strClean($title);
	  $text = strClean($text);
  
      $sql = 'UPDATE `news` SET title = "'.$title.'", poster = "'.$poster.'", text = "'.$text.'" WHERE id = "'.$id.'"';
	  mysql_query($sql) or die('<center><div class="news-no">Erreur SQL : '.$sql.'<br />'.mysql_error().'</div></center>');
	
	  echo  '<center><div class="news-ok">La news n°'.$id.' a bien été éditée.</div></center>';
	  
	}
	
  }
  
}
?>
