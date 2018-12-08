<?php

class index{
			
		
	
		public static function head(){
			
			return 'Ana Sayfa';
			
		}
		
		public static function content(){
			
			global $db;

			$destekKategorileri = $db->prepare("SELECT * FROM destek_kategori WHERE sid = ?");
			$destekKategorileri->execute(array($_SESSION['destek_server']));
			
			(kontrol::dosyaVarmi(TEMA.'homepage.php')) ? require_once(TEMA.'homepage.php') : print('dosya yok');
			
		}
	
}