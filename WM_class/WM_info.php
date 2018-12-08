<?php
class WM_info {
    public function kisalt( $kelime, $str = 10, $kisalt = ".." ) {
        if ( strlen( $kelime ) > $str ) {
            if ( function_exists( "mb_substr" ) )
                $kelime = mb_substr( $kelime, 0, $str, "UTF-8" ) . $kisalt;
            else
                $kelime = substr( $kelime, 0, $str ) . $kisalt;
        }
        return $kelime;
    }
    public function destek_durum( $tur, $i = 1 ) {
        if ( $tur == 0 ) {
            if ( $i == 1 ) {
                $l = "success";
            } else {
                $l = "yesil";
            }
            return '<label class="label label-' . $l . '"> Açık</label>';
        } else if ( $tur == 1 ) {
            if ( $i == 1 ) {
                $l = "warning";
            } else {
                $l = "turuncu";
            }
            return "<label class='label label-" . $l . "'> Yanıtlandı</label>";
        } else if ( $tur == 2 ) {
            if ( $i == 1 ) {
                $l = "primary";
            } else {
                $l = "kapalimavi";
            }
            return "<label class='label label-" . $l . "'> Oyuncu Yanıtı</label>";
        } else if ( $tur == 3 ) {
            if ( $i == 1 ) {
                $l = "info";
            } else {
                $l = "acikmavi";
            }
            return "<label class='label label-" . $l . "'> Sonuçlandı</label>";
        } else if ( $tur == 4 ) {
            if ( $i == 1 ) {
                $l = "danger";
            } else {
                $l = "kirmizi";
            }
            return "<label class='label label-" . $l . "'> Kapandı</label>";
        } else if ( $tur == 5 ) {
            if ( $i == 1 ) {
                $l = "success";
            } else {
                $l = "yesil";
            }
            return "<label class='label label-" . $l . "'> Ödeme Onaylandı</label>";
        } else if ( $tur == 6 ) {
            if ( $i == 1 ) {
                $l = "danger";
            } else {
                $l = "kirmizi";
            }
            return "<label class='label label-" . $l . "'> Ödeme Onaylanmadı</label>";
        }
    }
    public function efsun_detay( $id ) {
        $efsun = array(
             '1' => 'Max HP +',
            '2' => 'Max SP +',
            '3' => 'Yasam Enerjisi +',
            '4' => 'Zeka +',
            '5' => 'Guc +',
            '6' => 'ceviklik +',
            '7' => 'Saldiri Hizi +',
            '8' => 'Hareket Hizi +',
            '9' => 'Buyu Hizi',
            '10' => 'HP uretimi %',
            '11' => 'SP uretimi %',
            '12' => 'Zehirleme Degisimi %',
            '13' => 'Sersemletme Degisimi %',
            '14' => 'Yavaslik Degisimi %',
            '15' => 'Kritik Vurus sansi %',
            '16' => 'Delici Vurus sansi %',
            '17' => 'Yari insanlara Karsi Guclu %',
            '18' => 'Hayvanlara Karsi Guclu %',
            '19' => 'Orklara Karsi Guclu %',
            '20' => 'Mistiklere Karsi Guclu %',
            '21' => 'Ölumsuzlere Karsi Guclu %',
            '22' => 'seytanlara Karsi Guclu %',
            '23' => 'Hasar HP Tarafindan Emilicek %',
            '24' => 'Hasar SP Tarafindan Emilicek %',
            '25' => 'Dusmanin Spsini calma Sansi %',
            '26' => 'Vurus Yapildiginda Spyi geri calma %',
            '27' => 'Beden Karsisindaki Ataklarin Bloklanmasi %',
            '28' => 'Oklardan Korunma sansi %',
            '29' => 'Kilic Savunmasi %',
            '30' => 'cift-El Savunmasi %',
            '31' => 'Bicak Savunmasi %',
            '32' => 'can Savunmasi %',
            '33' => 'Yelpaze Savunmasi %',
            '34' => 'Oka Karsi Dayaniklilik %',
            '35' => 'Atese Karsi Dayaniklilik %',
            '36' => 'Simgeye Karsi Dayaniklilik %',
            '37' => 'Buyuye Karsi Dayaniklilik %',
            '38' => 'Ruzgar Dayanikliligi %',
            '39' => 'Vucut Darbesini Yansitma sansi %',
            '40' => 'Lanet Yansitilmasi %',
            '41' => 'Zehre Karsi Koyma %',
            '42' => 'Sp Yuklenmesi Degisti',
            '43' => 'Yang Dusme sansi %',
            '44' => 'Yang Dusme sansi %',
            '45' => 'Esya Dusme sansi %',
            '46' => 'Trank effekt zuwachs %',
            '47' => 'HP Yuklenmesi Degisti %',
            '48' => 'Sersemletme Karsisinda Bagisiklik %',
            '49' => 'Yavaslatma Karsisinda Bagisiklik %',
            '50' => 'imun gegen Sturzen',
            '52' => 'Bogenreichweite +',
            '53' => 'Saldiri Degeri +',
            '54' => 'Savunma +',
            '55' => 'Buyulu Saldiri Degeri +',
            '56' => 'Buyulu Savunma +',
            '58' => 'Max Dayaniklilik +',
            '59' => 'Savascilara Karsi Guclu %',
            '60' => 'Ninjalara Karsi Guclu %',
            '61' => 'Suralara Karsi Guclu %',
            '62' => 'samanlara Karsi Guclu %',
            '63' => 'Yaratiklara Karsi Guclu %',
            '64' => 'Saldiri Degeri +',
            '65' => 'Savunma +',
            '66' => 'EXP +?%',
            '67' => 'Dropchance [Gegenstände]',
            '68' => 'Dropchance [Gold]',
            '71' => 'Beceri Hasari %',
            '72' => 'Ortalama Zarar %',
            '73' => 'Widerstand gegen Fertigkeitsschaden',
            '74' => 'durchschn. Schadenswiderstand',
            '76' => 'iCafe exp-bonus',
            '77' => 'iCafe Chance auf erbeuten von gegenständen',
            '78' => 'Savasci Saldirilarina Karsi Savunma %',
            '79' => 'Ninja Saldirilarina Karsi Savunma %',
            '80' => 'Sura Saldirilarina Karsi Savunma %',
            '81' => 'saman Saldirilarina Karsi Savunma %' 
        );
        return $efsun[ $id ];
    }
    public function tarih_format( $f, $zt = 'now' ) {
        $z        = date( "$f", strtotime( $zt ) );
        $donustur = array(
             'Monday' => 'Pazartesi',
            'Tuesday' => 'Salı',
            'Wednesday' => 'Çarşamba',
            'Thursday' => 'Perşembe',
            'Friday' => 'Cuma',
            'Saturday' => 'Cumartesi',
            'Sunday' => 'Pazar',
            'January' => 'Ocak',
            'February' => 'Şubat',
            'March' => 'Mart',
            'April' => 'Nisan',
            'May' => 'Mayıs',
            'June' => 'Haziran',
            'July' => 'Temmuz',
            'August' => 'Ağustos',
            'September' => 'Eylül',
            'October' => 'Ekim',
            'November' => 'Kasım',
            'December' => 'Aralık',
            'Mon' => 'Pts',
            'Tue' => 'Sal',
            'Wed' => 'Çar',
            'Thu' => 'Per',
            'Fri' => 'Cum',
            'Sat' => 'Cts',
            'Sun' => 'Paz',
            'Jan' => 'Oca',
            'Feb' => 'Şub',
            'Mar' => 'Mar',
            'Apr' => 'Nis',
            'Jun' => 'Haz',
            'Jul' => 'Tem',
            'Aug' => 'Ağu',
            'Sep' => 'Eyl',
            'Oct' => 'Eki',
            'Nov' => 'Kas',
            'Dec' => 'Ara' 
        );
        foreach ( $donustur as $en => $tr ) {
            $z = str_replace( $en, $tr, $z );
        }
        return $z;
    }
    public function WM_rutbe( $rutbe ) {
        if ( $rutbe > 11999 ) {
            return "<font color='#59ABE3'><b>Kahraman</b></font>";
        } elseif ( $rutbe > 7999 ) {
            return "<font color='#3498db'><b>Soylu</b></font>";
        } elseif ( $rutbe > 3999 ) {
            return "<font color='darkblue'><b>İyi</b></font>";
        } elseif ( $rutbe > 1999 ) {
            return "<font color='#2980b9'><b>Arkadaşça</b></font>";
        } elseif ( $rutbe > -3999 ) {
            return "Tarafsız";
        } elseif ( $rutbe > -7999 ) {
            return "<font color='#EB974E'>Agresif</font>";
        } elseif ( $rutbe > -11999 ) {
            return "<font color='#EB974E'><b>Hileli</b></font>";
        } elseif ( $rutbe < -20000 ) {
            return "<font color='#e74c3c'>Kötü Niyetli</font>";
        } elseif ( $rutbe >= -20000 ) {
            return "<font color='red'><b>Zalim</b></font>";
        }
    }
    public function session_giris_sonlandir( ) {
        global $vt;
        unset( $_SESSION[ $vt->a( "isim" ) . "token" ] );
        unset( $_SESSION[ $vt->a( "isim" ) . "username" ] );
        unset( $_SESSION[ $vt->a( "isim" ) . "userid" ] );
    }
}
?>