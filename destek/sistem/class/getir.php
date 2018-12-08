<?php

class getir{
	
	public static function ayar($getir)
	{
		global $db;
		$cek = $db->prepare("SELECT $getir FROM ayarlar");
		$cek->execute();
		$fetch = $cek->fetch(PDO::FETCH_ASSOC);
		return $fetch[$getir];
	}
	
	public static function departmanKac($kid){
		global $db;
		$cek = $db->prepare("SELECT COUNT(id) FROM destek WHERE sid = ? && kid = ?");
		$cek->execute(array($_SESSION['destek_server'], $kid));
		return $cek->fetchColumn();
	}
	
	public static function durum($durum){
		if($durum == 0){
			return '<label class="label label-success"> Açık</label>';
		}
		else if($durum == 1){
			return '<label class="label label-warning"> Yanıtlandı</label>';
		}
		else if($durum == 2){
			return '<label class="label label-primary"> Oyuncu Yanıtı</label>';
		}
		else if($durum == 3){
			return '<label class="label label-info"> Sonuçlandı</label>';
		}
		else if($durum == 4){
			return '<label class="label label-danger"> Kapandı</label>';
		}
		else if($durum == 5){
			return '<label class="label label-success"> Ödeme Onaylandı</label>';
		}
		else if($durum == 6){
			return '<label class="label label-danger"> Ödeme Onaylanmadı</label>';
		}
	}
	
	public static function getirSayi($durum, $durum2 = false){
		global $db;
		if($durum2 == false){
		$cek = $db->prepare("SELECT COUNT(id) FROM destek WHERE sid = ? && acan = ? && durum = ?");
		$cek->execute(array($_SESSION['destek_server'], $_SESSION['destekUsername'], $durum));
		}
		else{
		$cek = $db->prepare("SELECT COUNT(id) FROM destek WHERE (sid = ? && acan = ?) && (durum = ? || durum = ?)");
		$cek->execute(array($_SESSION['destek_server'], $_SESSION['destekUsername'], $durum, $durum2));
		}
		return $cek->fetchColumn();
	}
	
	
    public static function zaman_cevir( $zaman, $tur = 1 ) {
        $zaman = strtotime( $zaman );
        if ( $tur == 1 ) {
            $zaman_farki = time() - $zaman;
            $ne          = "önce";
        } else {
            $zaman_farki = $zaman - time();
            $ne          = "sonra";
        }
        $saniye = $zaman_farki;
        $dakika = round( $zaman_farki / 60 );
        $saat   = round( $zaman_farki / 3600 );
        $gun    = round( $zaman_farki / 86400 );
        if ( $saniye < 60 ) {
            if ( $saniye == 0 ) {
                return "az " . $ne;
            } else {
                return $saniye . ' saniye ' . $ne;
            }
        } else if ( $dakika < 60 ) {
            return $dakika . ' dakika ' . $ne;
        } else if ( $saat < 24 ) {
            return $saat . ' saat ' . $ne;
        } else if ( $gun >= 1 ) {
            return $gun . ' gün ' . $ne;
        }
    }
	
}
	
