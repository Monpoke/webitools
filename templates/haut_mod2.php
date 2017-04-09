<?php // fichier reserve a l'inscrpition connexion ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<style type="text/css">
		
		body {
			<?php 
			
			if(!empty($conf['champ_22']))
				$bg2 = " url('".$conf['champ_22']."');\n background-size:cover";
			else
				$bg2='';
			?>
			background: #<?=$conf['champ_1'];?><?=$bg2;?>; /* 1 */
		}
		
		#centre {
			
			width: <?=$conf['champ_2'];?>px; /* 2 */
			margin: auto;
		}
		
		#contenu {
			<?php 
			
			if(!empty($conf['champ_23']))
				$bg2 = " url('".$conf['champ_23']."');\n background-size:cover";
			else
				$bg2='';
			?>
			background: #<?=$conf['champ_3'];?><?=$bg2;?>; /* 3 */
			width:<?=$conf['champ_4'];?>px; /* 4 */
			margin: auto;
			
			padding: <?=$conf['champ_5'];?>px; /* 5 */
			color: #<?=$conf['champ_6'];?>; /* 6 */
			
			-webkit-border-radius: <?=$conf['champ_7'];?>px; /* 7 */
			-moz-border-radius: <?=$conf['champ_7'];?>px;
			border-radius: <?=$conf['champ_7'];?>px;
			
			margin-top: <?=$conf['champ_8'];?>px; /* 8 */
			
			/* shadow A FAIRE */
			<?php 
			$bg=explode(';', $conf['champ_9']);
			?>
			-moz-box-shadow: 0px 0px <?=$bg[0];?>px <?=$bg[1];?>px #<?=$bg[2];?>; /* 9 */
			-webkit-box-shadow: 0px 0px <?=$bg[0];?>px <?=$bg[1];?>px #<?=$bg[2];?>;
			-o-box-shadow: 0px 0px <?=$bg[0];?>px <?=$bg[1];?>px #<?=$bg[2];?>;
			box-shadow: 0px 0px <?=$bg[0];?>px <?=$bg[1];?>px #<?=$bg[2];?>;
			filter:progid:DXImageTransform.Microsoft.Shadow(color=#<?=$bg[2];?>, Direction=NaN, Strength=<?=$bg[0];?>);
		} 
		
		.perso {
			
			color: #<?=$conf['champ_29'];?>; /* 10 */
			border-radius: <?=$conf['champ_10'];?>px; /* 10 */
			height: <?=$conf['champ_28'];?>px; /* 10 (bis) */
			background: #<?=$conf['champ_14'];?>; /* 14 */
			border: solid 1px black<?//=$conf['champ_12']; plus tard?>; /* 12 */
			text-align: center; 
		}
		
		.perso:focus {
			
			background: #<?=$conf['champ_25'];?>; /* 14 (2) */
		}
		
		input[type='submit'] {
			border-radius: <?=$conf['champ_15'];?>px; /* 15  */
			
			width: <?=$conf['champ_16'];?>%; /* 16 */
			margin-left: 25%; /* 17 */
			margin-top: 25px;/* 17 (bis) */
			
			height: <?=$conf['champ_18'];?>px; /* 18 */
			vertical-align: middle; 
			text-align: center;
		}
		
		.td {
			text-align: <?=$conf['champ_19'];?>; /* 19 */
		}
		
		.erreur {
		
			color: #<?=$conf['champ_20'];?>; /* 20 */
			font-weight: bold;
			
			text-align: center;
		}
		
		.confirm {
			color: #<?=$conf['champ_26'];?>; /* 20 (bis) */
			font-weight: bold;
			text-align: center;
			
		}
		
		.go {
			text-decoration: none;
			color: #<?=$conf['champ_21'];?>; /* 21 */
			font-style: italic;
			
		}
		
		.go:hover {
			text-decoration: underline;
			color: #<?=$conf['champ_27'];?>; /* 21 (bis) */
			font-style: italic;
			
		}
		
		
		
		#pub {
			position: fixed;
			bottom:0;
			left: 0;
			padding: 5px;
			-webkit-border-top-right-radius: 5px;
			-moz-border-radius-topright: 5px;
			border-top-right-radius: 5px;
			background: #<?=$conf['champ_3'];?><?=$bg2;?>;
			color: #<?=$conf['champ_6'];?>;
		
		}
		

	</style>
	
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-34275806-2']);
  _gaq.push(['_setDomainName', 'plumedor.fr']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
	
	<title>
		<?=$titre;?>
	</title> 
</head>
<body>
