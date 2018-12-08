<?php
class WM_vt_settings {
    private $server = "";
    public $mail_tema = "";
    private $mail_tema_varmi = "";
    private $yer = "";
    public function __construct( $server ) {
        global $konum;
        if ( $konum == "." ) {
            $this->yer = "";
        } else {
            $this->yer = "../";
        }
        $this->server          = $server;
        $this->mail_tema_varmi = $this->yer . "WM_theme/WM_mail/" . $this->a( "mail_tema" );
        if ( file_exists( $this->mail_tema_varmi ) ) {
            $this->mail_tema = $this->yer . "WM_theme/WM_mail/" . $this->a( "mail_tema" ) . "/";
        } else {
            $this->mail_tema = $this->yer . "WM_theme/WM_mail/default/";
        }
    }
    public function url( $konum ) {
        $url = json_decode( $this->a( "linkler" ) );
        return $url[ $konum ];
    }
    public function sosyal( $konum ) {
        $sosyal = json_decode( $this->a( "sosyal_ag" ) );
        return $sosyal[ $konum ];
    }
    public function istatistik( $konum ) {
        $istatistik = json_decode( $this->a( "istatistik" ) );
        return $istatistik[ $konum ];
    }
    public function bildirim_gonder( $alan, $tur, $bildirim, $olay_yeri, $alici_tur = 1 ) {
        global $db;
        $insert = $db->prepare( "INSERT INTO bildirim SET sid = ?, alan = ?, tur = ?, bildirim = ?, olay_yeri = ?, durum = ?, tarih = ?, alici_tur = ?" );
        $ekle   = $insert->execute( array(
             server,
            $alan,
            $tur,
            $bildirim,
            $olay_yeri,
            1,
            date( "Y-m-d H:i:s" ),
            $alici_tur 
        ) );
    }
    public function a( $deger ) {
        global $db;
        $query = $db->prepare( "SELECT * FROM server WHERE id = ?" );
        $query->execute( array(
             $this->server 
        ) );
        $fetch = $query->fetch( PDO::FETCH_ASSOC );
        return @$fetch[ $deger ];
    }
    public function bildirim_url( $tur, $olay_yeri ) {
        if ( $tur == 1 ) {
            return 'kullanici/teknik-destek-detay?id=' . $olay_yeri;
        } else if ( $tur == 2 ) {
            return 'kullanici/teknik-destek-detay?id=' . $olay_yeri;
        } else if ( $tur == 3 ) {
            return 'kullanici/basvuru?detay_basvuru=' . $olay_yeri;
        }
    }
    public function token_ekle( $tur, $kim, $token = "" ) {
        global $db, $ayar;
        if ( $token == "" ) {
            $token = $ayar->token_rastgele;
        }
        $sil = $db->prepare( "DELETE FROM token WHERE login = ? && sid = ? && tur = ?" );
        $sil->execute( array(
             $kim,
            $this->server,
            $tur 
        ) );
        $insert = $db->prepare( "INSERT INTO token SET sid = ?, login = ?, tur = ?, token = ?" );
        $ekle   = $insert->execute( array(
             $this->server,
            $kim,
            $tur,
            $token 
        ) );
    }
    public function tokenleri_sil( $tur, $kim ) {
        global $db;
        $sil = $db->prepare( "DELETE FROM token WHERE sid = ? && tur = ? && login = ?" );
        $sil->execute( array(
             $this->server,
            $tur,
            $kim 
        ) );
    }
    public function uye( $cek ) {
        global $odb;
        $query = $odb->prepare( "SELECT $cek FROM account WHERE id = ? " );
        $query->execute( array(
             $_SESSION[ $this->a( "isim" ) . "userid" ] 
        ) );
        if ( $query->rowCount() ) {
            $fetch = $query->fetch( PDO::FETCH_ASSOC );
            return $fetch[ $cek ];
        } else {
            header( 'Location: index.php' );
        }
    }
    public function hata_gonder( $icerik, $tur = 0, $kullanici = "" ) {
        global $db;
        $query = $db->prepare( "INSERT INTO hatalar SET sid = ?, tur = ?, kullanici = ?, icerik = ?, tarih = ?" );
        $ekle  = $query->execute( array(
             $this->server,
            $tur,
            $kullanici,
            $icerik,
            date( "Y-m-d H:i:s" ) 
        ) );
    }
    public function mail_gonder( $kime, $konu, $icerik ) {
        global $mail, $WMclass, $yer;
        require_once $this->yer . WM_Plugins_lib . 'WM_smtp/class.phpmailer.php';
        require_once $this->mail_tema . 'index.php';
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug  = false;
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = $WMclass->ayar( "mail_secure" );
        $mail->Host       = $WMclass->ayar( "mail_host" );
        $mail->Port       = $WMclass->ayar( "mail_port" );
        $mail->IsHTML( true );
        $mail->SetLanguage( "tr", "phpmailer/language" );
        $mail->CharSet  = "utf-8";
        $mail->Username = $WMclass->ayar( "mail_user" );
        $mail->Password = $WMclass->ayar( "mail_pass" );
        $mail->SetFrom( $WMclass->ayar( "mail_profil" ), $WMclass->ayar( "mail_isim" ) );
        $mail->AddAddress( $kime );
        $mail->Subject = $konu;
        $mail->Body    = mail_icerik( $icerik );
        return $mail->send();
    }
    public function online_kontrol( $name ) {
        global $odb, $ayar;
        $online = $odb->prepare( "SELECT COUNT(name) as count FROM player.player WHERE name = ? AND DATE_SUB(NOW(), INTERVAL ? MINUTE) < last_play" );
        $online->execute( array(
             $name,
            60 
        ) );
        if ( $online->fetchColumn() == 0 ) {
            return '<img src="' . $ayar->WMimg . 'off.png">';
        } else {
            return '<img src="' . $ayar->WMimg . 'on.png">';
        }
    }
    public function lonca( $id, $deger = "name" ) {
        global $odb;
        $query = $odb->prepare( "SELECT $deger FROM player.guild WHERE id = ?" );
        $query->execute( array(
             $id 
        ) );
        $fetch = $query->fetch( PDO::FETCH_ASSOC );
        return $fetch[ $deger ];
    }
    public function karakter( $id, $deger ) {
        global $odb;
        $query = $odb->prepare( "SELECT $deger FROM player.player WHERE id = ?" );
        $query->execute( array(
             $id 
        ) );
        if ( $query->rowCount() ) {
            $fetch = $query->fetch( PDO::FETCH_ASSOC );
            return $fetch[ $deger ];
        } else {
            return 'HATALI ID';
        }
    }
    public function zaman_bittimi( $bitis ) {
        $hesapla = strtotime( $bitis ) - time();
        if ( $hesapla < 0 ) {
            return true;
        } else {
            return false;
        }
    }
    public function kullanici_log( $icerik ) {
        global $db;
        if ( $this->a( "kullanici_log" ) == 1 ) {
            $insert = $db->prepare( "INSERT INTO kullanici_log SET sid = ?, kullanici = ?, icerik = ?, tarih = ?" );
            $ekle   = $insert->execute( array(
                 $this->server,
                $_SESSION[ $this->a( "isim" ) . "username" ],
                $icerik,
                date( "Y-m-d H:i:s" ) 
            ) );
        }
    }
    public function yonlendir( $nereye ) {
?>
<SCRIPT LANGUAGE="JavaScript">
<!-- 
window.location="<?= $nereye; ?>";
// -->
</script>

<?php
    }
}
?>