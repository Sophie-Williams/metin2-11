<?php


date_default_timezone_set('Europe/Istanbul');

$sayfa = gvn::get('sayfa');

if(uye::girdimi() == true)
{

	if (empty($sayfa)) {
		
		$page = "index";
		
		(kontrol::dosyaVarmi(dirname(__FILE__) . '/page/index.php')) ? require_once(dirname(__FILE__) . '/page/index.php') : require_once(dirname(__FILE__) . '/page/error.php');
		
		
	} else {
		
		$page = $sayfa;
		
		(kontrol::dosyaVarmi(dirname(__FILE__) . '/page/' . $sayfa . '.php')) ? require_once(dirname(__FILE__) . '/page/' . $sayfa . '.php') : require_once(dirname(__FILE__) . '/page/error.php');
		
	}

	(kontrol::dosyaVarmi(TEMA . 'index.php')) ? require_once(TEMA . 'index.php') : print('Tema dosyası bulunamadı');

}
else
{
	
	if($sayfa == 'sifremi-unuttum'){
		(kontrol::dosyaVarmi(dirname(__FILE__) . '/page/' . $sayfa . '.php')) ? require_once(dirname(__FILE__) . '/page/' . $sayfa . '.php') : require_once(dirname(__FILE__) . '/page/error.php');
	}
	else if(isset($_SESSION["guvenliGiris"]) && $sayfa == 'guvenliGiris'){
		(kontrol::dosyaVarmi(dirname(__FILE__) . '/page/' . $sayfa . '.php')) ? require_once(dirname(__FILE__) . '/page/' . $sayfa . '.php') : require_once(dirname(__FILE__) . '/page/error.php');
	}
	else{
	(kontrol::dosyaVarmi(TEMA . 'giris.php')) ? require_once(TEMA . 'giris.php') : print('Tema dosyası bulunamadı');
	}
		
	
}
