<?php 


// Submit those variables to the server
$post_data = array(
    'nomsite' => 'monpoke',
    'password' => 'pokemn1',
);
 
// Send a request to example.com 
$result = post_request('http://www.webidev.com/fr/WebiConnect', $post_data);
 
if ($result['status'] == 'ok'){
 
    // print the result of the whole request:
    if(preg_match("#http://www\.webidev.com/Images/Decors/Warning\.png#", $result['content'])){
		echo "Echoue !";
	} else {
		echo $result['content'];
	}
 
}
else {
    echo 'A error occured: ' . $result['error']; 
}