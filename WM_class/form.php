<?php

class form{
	
	public static function basari($yazi){
		echo '<script>sweetAlert("Başarı", "'.$yazi.'", "success");</script>';
	}
	public static function hata($yazi){
		echo '<script>sweetAlert("Hata", "'.$yazi.'", "error");</script>';
	}
	
}

?>