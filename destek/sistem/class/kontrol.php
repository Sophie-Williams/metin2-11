<?php

class kontrol{

	public static function dosyaVarmi($dosya){
	
			return (file_exists($dosya)) ? true : false;
		
	}
	
	

}	
