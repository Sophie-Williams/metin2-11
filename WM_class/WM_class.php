<?php
class WMclass {
    public function ayar( $cek ) {
        global $db;
        $query = $db->query( "SELECT * FROM ayarlar" );
        $fetch = $query->fetch( PDO::FETCH_ASSOC );
        return $fetch[ $cek ];
    }
    public function tema( $id ) {
        global $db;
        $query = $db->prepare( "SELECT tema FROM server WHERE id = ?" );
		$query->execute(array($id));
		$fetch = $query->fetch();
        return $fetch[ "tema" ];
    }
    public function bosluk_sil( $deger ) {
        $deger = preg_replace( "/\s+/", " ", $deger );
        $deger = trim( $deger );
        return $deger;
    }
    public function ip_kontrol( $ip ) {
        $parcala = explode( '.', $ip );
        if ( strlen( $ip ) > 15 ) {
            return false;
        } else if ( count( $parcala ) != 4 ) {
            return false;
        } else if ( strlen( $parcala[ 0 ] ) > 3 OR strlen( $parcala[ 1 ] ) > 3 OR strlen( $parcala[ 2 ] ) > 3 OR strlen( $parcala[ 3 ] ) > 3 ) {
            return false;
        } else {
            return true;
        }
    }
    public function server_yazdir( $server ) {
        return html_entity_decode( '

$konum = "..";

require_once "../WM_settings/WMayar.php";
define("Sayfa_html", "../WM_Sayfalar/html_sayfa/".@$_GET["islem"]."/");

define("server", ' . $server . ');

if(!isset($_SESSION["server_vt"]) || $_SESSION["server_vt"] != server)
{
	
$_SESSION["server_vt"] = server;
	
}

$ayar = new WMayar("..", server);

$vt = new WM_vt_settings(server);	

$ana_sayfa_tema = json_decode($WMclass->tema(server));

if($ana_sayfa_tema[0] == "tema")
{


define("WM_tema", "../WM_theme/WM_tema/".$ana_sayfa_tema[1]."/");

$page = $WMkontrol->WM_get($WMkontrol->WM_eng(@$_GET["islem"]));

if(file_exists(WM_tema)){

if(!$page)
{

$wmcp = new index;

}
else
{

$wmcp = new $page;

}
		
require_once WM_tema."index.php";	
}
else
{
	
echo "dizin bulunamadı";
	
}

}

else
{
	
define("WM_bakim", "../WM_theme/WM_bakim/".$ana_sayfa_tema[1]."/");

if(file_exists(WM_bakim))
{
	
require WM_bakim."index.php";	
	
}
else
{
	
echo "Bakım dizini bulunamadı";
	
}

	
}



?>' );
    }
}
?>