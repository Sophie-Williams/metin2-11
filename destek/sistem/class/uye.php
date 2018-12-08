<?php

class uye{

		
	public static function girdimi(){
		
		if(isset($_SESSION['destekUserıd']) && isset($_SESSION['destekUsername']) && isset($_SESSION['destek_giris']) && isset($_SESSION['destek_server'])){
			return true;
		}
		else{
			return false;
			uye::sonlandir();
		}
		
	}
	
	public static function sonlandir(){
		
		session_destroy();
		
		ob_end_flush();
		
		header('Location: #');
		
	}
	
	

}	


