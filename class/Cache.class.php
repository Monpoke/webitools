<?php 

class Cache {


	private  $page="";
	private  $init=false;
	private  $page_origine=array();

	
	
	function setPage($page, $type="private", $extension="cac", $suffixe=""){
		
		if($type!="private" && $type!="public")
			return false;
			
		if(!empty($suffixe))
			$suffixe="-".strtolower($suffixe);
			
		$this->init=true;
			
		$this->page=SROOT."/cache/$type/".sha1($page).$suffixe.".".$extension;
		$this->page_origine=array('nom' => $page, 'extension' => $extension, 'suffixe' => $suffixe);
		
		
		return true;
	}
	
	
	function checkCache($type="private") {

		if($this->init==false)
			return false;
			
		if($type!="private" && $type!="public")
			return false;
			
		
		// on verifie si le fichier existe déjà
		if(!is_file($this->page))
			return false;
			
		else
			return true;
	
	}
	
	function dateCache($type="private") {
	
		if($this->init==false)
			return false;
			
		if($type!="private" && $type!="public")
			return false;
			
		$path = SROOT."/$type/".$this->page;
		
		// on verifie si le fichier existe déjà
		if(!is_file($path))
			return false;
			
		else
			return filemtime($path);
	
	}
	
	function freeCache(){ // $temps -> 1 jour / defaut (en s) ||$fichier -> fichier particulier
		
		// public et même prive
		$path_private = SROOT."/cache/private/";
		
		$fichiers=scandir($path_private);
		$n=0;
		
		foreach($fichiers as $fichier2){
			if($fichier2==".htaccess" or $fichier2 == ".." or $fichier2 == ".")
				continue;
				
		
			unlink($path_private.$fichier2);
			$n++;
			
		}
	
		// public et même prive
		$path_private = SROOT."/cache/public/";
		
		$fichiers=scandir($path_private);
		$n=0;
		
		foreach($fichiers as $fichier2){
			if($fichier2==".htaccess" or $fichier2 == ".." or $fichier2 == ".")
				continue;
				
		
			unlink($path_private.$fichier2);
			$n++;
			
		}
	
		
	
	}
	
	
	function deleteCache($id_site){ // $temps -> 1 jour / defaut (en s) ||$fichier -> fichier particulier
		
		$fichier = sha1($id_site);
		
		// public et même prive
		$path_private = SROOT."/cache/private/";
		
		$fichiers=scandir($path_private);
		$n=0;
		
		foreach($fichiers as $fichier2){
			if($fichier2==".htaccess" or $fichier2 == ".." or $fichier2 == ".")
				continue;
				
			if(preg_match("#^$fichier#", $fichier2)){
				unlink($path_private.$fichier2);
				$n++;
			}
			
		
		}
		
		// public et même prive
		$path_public = SROOT."/cache/public/";
		
		$fichiers=scandir($path_public);
		
		
		foreach($fichiers as $fichier2){
			if($fichier2==".htaccess" or $fichier2 == ".." or $fichier2 == ".")
				continue;
				
			if(preg_match("#^$fichier#", $fichier2)){
				unlink($path_public.$fichier2);
				$n++;
			}
			
		
		}
		
		
		return $n;
		
		
		
	}
	
	
	
	
	function getCache(){
	
		if($this->init==false)
				return false;
				
	
		 if($this->checkCache()){
		 
			ob_start();
			require($this->page);
			
			$contenu = ob_get_clean();
			
			return $contenu;
		 
		 }
		 
		 return false;
	
	}
	
	
	function updateCache($contenu){
	
		if($this->init==false)
			return false;
			
		if($fichier = fopen($this->page, 'a')){
			
			if(ftruncate($fichier, 0)){
			
				if(fputs($fichier, $contenu)) {// On écrit le nouveau nombre de pages vues
				 
					fclose($fichier);
					return true;
				}
				// else
					// echo "erreur 3";
			}
			// echo "erreur 2";
		}
		// else
			// echo "erreur";
		
		return true;
	
	}
	
	
	
}