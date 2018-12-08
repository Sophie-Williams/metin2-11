<?php
if ( !isset( $izin_verme ) ) {
    die( "Buraya giriş izniniz yoktur." );
    exit;
} else if ( !isset( $_SESSION[ "market_server" ] ) ) {
    die( "Server Bulunamadı" );
    exit;
}
@$kategori = gvn::get('kategori');
@$item = gvn::get('item');
@$id = gvn::get('id');
$kontrol = $db->prepare( "SELECT * FROM market_item WHERE kid = ? && id = ? && sid = ?" );
$kontrol->execute( array(
     $kategori,
    $id,
    $_SESSION[ "market_server" ] 
) );
if ( $kontrol->rowCount() ) {
    $ifetch = $kontrol->fetch( PDO::FETCH_ASSOC );
    require_once WM_market . 'lightbox.php';
} else {
}
?>