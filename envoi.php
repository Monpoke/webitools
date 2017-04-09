<?php 
if(!isset($envoi))
	exit;

/**
* IDENTIFIANTS A MODIFIER POUR ENVOYER UN MESSAGE A PARTIR D'UN COMPTE WEBIDEVs
*/
$infos_connect = array(
    'nomsite' => 'webitools',
    'password' => 'pokepoke',
);


$dir = ROOT. "/tmp/".sha1($_SERVER['REMOTE_ADDR'])."_msg".rand(0,99).".tmp";

// CONNEXION
$ch = curl_init();
curl_setopt($ch, CURLOPT_COOKIEJAR, $dir);
curl_setopt($ch, CURLOPT_COOKIEFILE, $dir);
curl_setopt($ch, CURLOPT_URL,"http://www.webidev.com/WebiConnect");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
curl_setopt($ch, CURLOPT_USERAGENT, "WebiTools");
curl_setopt($ch, CURLOPT_POSTFIELDS, "nomsite={$infos_connect['nomsite']}&password={$infos_connect['password']}");

ob_start();
	echo curl_exec ($ch); 
$t=ob_get_clean();

curl_close ($ch);

unset($ch);


// POSTE UN MESSAGE

if(empty($msg_message)){
$msg_message = <<<EOF
Erreur lors de l'envoi :/
EOF;

}

$message = utf8_encode($msg_message);

if(empty($msg_titre))
	$msg_titre = "Missing Title";
	
$titre=utf8_encode($msg_titre);

if(!isset($msg_to))
	exit;
	
if(!is_array($msg_to)){
$msg_to = strtolower($msg_to);

$ch = curl_init();
curl_setopt($ch, CURLOPT_COOKIEJAR, $dir);
curl_setopt($ch, CURLOPT_COOKIEFILE, $dir);
curl_setopt($ch, CURLOPT_URL,"http://www.webidev.com/fr/WebiBAL?New=1");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "WebiTools");
curl_setopt($ch, CURLOPT_POSTFIELDS, "ToType=Webmaster&To=$msg_to&Titre=$titre&Message=$message");

ob_start();
	echo curl_exec ($ch); 
$t=ob_get_clean();

curl_close ($ch);



unset($ch);

} else {

	$i=1;
	foreach($msg_to as $to){
	
				
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_COOKIEJAR, $dir);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $dir);
		curl_setopt($ch, CURLOPT_URL,"http://www.webidev.com/fr/WebiBAL?New=1");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "WebiTools");
		curl_setopt($ch, CURLOPT_POSTFIELDS, "ToType=Webmaster&To=$to&Titre=$titre&Message=$message");

		ob_start();
			echo curl_exec ($ch); 
		$t=ob_get_clean();

		curl_close ($ch);



		unset($ch);
		
		if($i==10){
			sleep(1);
			$i=10;
		} else
			$i++;
		
	}

}

// DECONNEXION
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);	
curl_setopt($ch, CURLOPT_COOKIEFILE, $dir);
curl_setopt($ch, CURLOPT_COOKIEJAR, $dir);
curl_setopt($ch, CURLOPT_URL,"http://www.webidev.com/WebiDeco");

curl_exec($ch);

curl_close ($ch);

if(file_exists($dir))
	unlink($dir);
