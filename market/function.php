<?php
if ( !isset( $izin_verme ) ) {
    die( "Buraya giriş izniniz yoktur." );
    exit;
}
function muye( $deger )
{
    global $odb;
    $query = $odb->prepare( "SELECT $deger FROM account WHERE id = ? && login = ?" );
    $query->execute( array(
         $_SESSION[ "market_userid" ],
        $_SESSION[ "market_user" ] 
    ) );
    $fetch = $query->fetch();
    return $fetch[ $deger ];
}
function depo_kontrol( )
{
    global $odb;
    $query = $odb->prepare( "SELECT pos FROM player.item WHERE owner_id = ? && window= ? ORDER BY id DESC LIMIT 0,1" );
    $query->execute( array(
         $_SESSION[ "market_userid" ],
        'MALL' 
    ) );
    if ( $query->rowCount() ) {
        $fetch = $query->fetch( PDO::FETCH_ASSOC );
        if ( $fetch[ "pos" ] == "" ) {
            $pos = 0;
        } else if ( $fetch[ "pos" ] >= 44 ) {
            $pos = 86;
        } else {
            $pos = $fetch[ "pos" ] + 1;
        }
    } else {
        $pos = 0;
    }
    return $pos;
}
function ayni_kontrol( $array, $j )
{
    $values  = array_count_values( $array );
    $hatalar = array( );
    try {
        for ( $i = 0; $i < $j; $i++ ) {
            if ( $values[ $array[ $i ] ] > 1 ) {
                throw new exception( '' );
            }
        }
    }
    catch ( Exception $e ) {
        $hatalar[ ] = $e->getMessage();
    }
    if ( count( $hatalar ) == 0 ) {
        return false;
    } else {
        return true;
    }
}
function efsun_kontrol( $array, $j, $itemtur )
{
    global $db;
    $hatalar = array( );
    try {
        for ( $i = 0; $i < $j; $i++ ) {
            $kontrol = $db->prepare( "SELECT id FROM market_efsun WHERE tur LIKE ? && sid = ? && id = ?" );
            $kontrol->execute( array(
                 '%' . $itemtur . '%',
                $_SESSION[ "market_server" ],
                $array[ $i ] 
            ) );
            $kontrol = $kontrol->rowCount();
            if ( $kontrol == 0 ) {
                throw new exception( '' );
            }
        }
    }
    catch ( Exception $e ) {
        $hatalar[ ] = $e->getMessage();
    }
    if ( count( $hatalar ) == 0 ) {
        return false;
    } else {
        return true;
    }
}
function tas_kontrol( $array, $j, $itemtur )
{
    global $db;
    $hatalar = array( );
		
    try {
        for ( $i = 0; $i < $j; $i++ ) {
            $kontrol = $db->prepare( "SELECT id FROM market_tas WHERE id = ? && tur = ? && sid = ?" );
            $kontrol->execute( array(
                 $array[ $i ],
                $itemtur,
                $_SESSION[ "market_server" ] 
            ) );
            $kontrol = $kontrol->rowCount();
            if ( $kontrol == 0 ) {
                throw new Exception( '' );
            }
        }
    }
    catch ( Exception $e ) {
        $hatalar[ ] = $e->getMessage();
    }
    if ( count( $hatalar ) == 0 ) {
        return false;
    } else {
        return true;
    }
}
function ep_dusur( $miktar )
{
    global $odb, $vt;
    $update = $odb->prepare( "UPDATE account SET coins = coins - ? WHERE id = ? && login = ?" );
    $update->execute( array(
         $miktar,
        $_SESSION[ "market_userid" ],
        $_SESSION[ "market_user" ] 
    ) );
    if ( !$update ) {
        $vt->hata_gonder( $_SESSION[ "market_user" ] . " adlı üye itemi başarıyla satın aldı fakat " . $miktar . " ep kullanıcıdan alınırken bir hata meydana geldi" );
    }
}
function log_ekle( $alinan, $fiyat, $tur, $log = "", $vnum = 19 )
{
    global $db;
    $insert = $db->prepare( "INSERT INTO market_log SET sid = ?, tur = ?, karakter = ?, alinan = ?, fiyat = ?, log = ?, tarih = ?, vnum = ?" );
    $ekle   = $insert->execute( array(
         $_SESSION[ "market_server" ],
        $tur,
        $_SESSION[ "market_user" ],
        $alinan,
        $fiyat,
        $log,
        date( "Y-m-d H:i:s" ),
        $vnum 
    ) );
}
function server_detay( $deger )
{
    global $db;
    $query = $db->prepare( "SELECT $deger FROM server WHERE id = ?" );
    $query->execute( array(
         $_SESSION[ "market_server" ] 
    ) );
    $fetch = $query->fetch();
    return $fetch[ $deger ];
}
function duyuru_listele( )
{
    global $db, $WMinf;
    $duyurular = $db->prepare( "SELECT * FROM market_duyuru WHERE sid = ?" );
    $duyurular->execute( array(
         $_SESSION[ "market_server" ] 
    ) );
    foreach ( $duyurular as $duyuru ) {
?>

<li><a href="duyuru/<?= $duyuru[ "seo" ]; ?>.html"><i class="fa fa-tint"></i> <?= $WMinf->kisalt( $duyuru[ "konu" ], 150 ); ?></a></li>

<?php
    }
}
function zaman_cevir( $zaman, $tur = 1 )
{
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
function tas( $id )
{
    $oku  = file_get_contents( "taslar.txt" );
    $kes  = explode( '"' . $id . '"', $oku );
    $kes2 = explode( ",", $kes[ 1 ] );
    $kes3 = explode( '"', $kes2[ 1 ] );
    return $kes3[ 1 ];
}
?>