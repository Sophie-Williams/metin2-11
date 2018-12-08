<?php  
if(!isset($izin_verme)){ die("Buraya giriş izniniz yoktur."); exit;}

else if(!isset($_SESSION["market_server"]) && !isset($_SESSION["market_user"]) && !isset($_SESSION["market_userid"]) && !isset($_SESSION["market_token"])) { die("Kullanıcı girişi yapmadan bu sayfaya erişemezsiniz"); exit;}

$islem = gvn::get('islem');

if($islem == "aldiklarim")
{
	
@$id = gvn::get('id');

if(!$id || $id == "" || !isset($id))
{
	
require_once WM_market.'aldiklarim.php';	
	
}
else
{

$kontrol = $db->prepare("SELECT * FROM market_log WHERE sid = ? && karakter = ? && id = ? && tur = ?");
$kontrol->execute(array($_SESSION["market_server"], $_SESSION["market_user"], 2));

if($kontrol->rowCount())
{
	
$fetch = $kontrol->fetch(PDO::FETCH_ASSOC);
	
require_once WM_market.'aldiklarim_detay.php';	
	
}
else
{
	
require_once WM_market.'aldiklarim.php';	
	
}

}
	
}

?>