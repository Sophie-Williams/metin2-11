<?php
$izin_verme = true;
require_once '../WM_settings/WMayar.php';
define( 'server_kontrol', @$_SESSION[ "market_server" ] );


if ( !server_kontrol || server_kontrol == '' || !isset( $_SESSION[ "market_user" ] ) || !isset( $_SESSION[ "market_token" ] ) || !isset( $_SESSION[ "market_userid" ] ) ) {
    if ( isset( $_POST[ "girisyap" ] ) ) {
        $serverid = addslashes( intval( $_POST[ "server" ] ) );
        $ayar     = new WMayar( "..", $serverid );
        $username = gvn::post('username');
        $password = gvn::post('password');
        $kontrol  = $db->prepare( "SELECT id,isim FROM server WHERE id = ?" );
        $kontrol->execute( array(
             $serverid 
        ) );
        if ( !$serverid || !$username || !$password ) {
            $bilgi = '<div class="alert alert-danger"><b><i class="fa fa-warning"></i> HATA</b> Boş Alan Bırakamazsınız. ! </div>';
        } else {
            if ( $kontrol->rowCount() ) {
                global $odb;
                $kullanici_kontrol = $odb->prepare( "SELECT login,id,status FROM account WHERE login = ? && password = PASSWORD(?)" );
                $kullanici_kontrol->execute( array(
                     $username,
                    $password 
                ) );
                if ( $kullanici_kontrol->rowCount() ) {
                    $fetch = $kullanici_kontrol->fetch( PDO::FETCH_ASSOC );
                    if ( $fetch[ "status" ] == "block" || $fetch[ "status" ] == "BLOCK" ) {
                        $bilgi = '<div class="alert alert-warning"><b><i class="fa fa-warning"></i> HATA</b> Hesabınız Banlandığı için giriş yapamıyorsunuz. ! </div>';
                    } else {
                        $_SESSION[ "market_server" ] = $serverid;
                        $_SESSION[ "market_user" ]   = $username;
                        $_SESSION[ "market_userid" ] = $fetch[ "id" ];
                        $_SESSION[ "market_token" ]  = session_id();
                        $bilgi                       = '<div class="alert alert-success"><b><i class="fa fa-check"></i> BAŞARI</b> Başarıyla giriş yaptınız 3 saniye sonra yönlendirilceksiniz. ! </div><meta http-equiv="refresh" content="3;URL=index.php">';
                    }
                } else {
                    $bilgi = '<div class="alert alert-danger"><b><i class="fa fa-warning"></i> HATA</b> Kullanıcı Adınız veya Şifreniz Yanlış</div>';
                }
            } else {
                $bilgi = '<div class="alert alert-danger"><b><i class="fa fa-warning"></i> HATA</b> Böyle bir server bulunamadı</div>';
            }
        }
    } else {
        $ayar = new WMayar( ".." );
    }
    function market_serverlar( )
    {
        global $db;
        $query = $db->prepare( "SELECT id,isim FROM server ORDER BY id" );
        $query->execute();
        foreach ( $query as $row ) {
            echo '<option value="' . $row[ "id" ] . '">' . $row[ "isim" ] . '</option>';
        }
    }
    if ( file_exists( '../WM_theme/WM_market/' . $WMclass->ayar( "market_tema" ) ) ) {
        $tema = $WMclass->ayar( "market_tema" ) . '/';
    } else {
        $tema = "default/";
    }
    define( 'WM_market', '../WM_theme/WM_market/' . $tema );
    require_once WM_market . 'giris.php';
} else {
    $ayar = new WMayar( "..", $_SESSION[ "market_server" ] );
    $vt   = new WM_vt_settings( $_SESSION[ "market_server" ] );
    require_once 'function.php';
    define( 'BASE_URL', $WMclass->ayar( "base" ) . 'market/' );
    if ( file_exists( '../WM_theme/WM_market/' . $WMclass->ayar( "market_tema" ) ) ) {
        $tema = $WMclass->ayar( "market_tema" ) . '/';
    } else {
        $tema = "default/";
    }
    define( 'WM_market', '../WM_theme/WM_market/' . $tema );
    @$kategori_get = gvn::get('kategori');
    @$sayfalar = gvn::get('sayfa');
    if ( !$sayfalar ) {
        require_once WM_market . 'index.php';
    } else if ( $sayfalar == "lightbox" ) {
        require_once 'lightbox.php';
    } else if ( $sayfalar == "kullanici" ) {
        require_once 'kullanici.php';
    } else if ( $sayfalar == "satin_al" ) {
        require_once 'sonuc.php';
    } else {
        if ( file_exists( WM_market . $sayfalar . '.php' ) ) {
            require_once WM_market . $sayfalar . '.php';
        } else {
            require_once WM_market . 'index.php';
        }
    }
}
?>