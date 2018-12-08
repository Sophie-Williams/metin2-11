<?php

class Post_bugdankurtar{
	
	public static function post(){
		
		global $vt, $ayar, $odb;
				
            $karakter     = gvn::post('bugdan_kurtarilcak');
            $kurtar_token = gvn::post('kurtar_token');
            if ( !$kurtar_token ) {
                form::hata( "Token Yok" );
            } else if ( $kurtar_token != $ayar->sessionid ) {
                form::hata( "Token hatası" );
            } else if ( $karakter == 1 ) {
                form::hata( "Kurtarılcak Karakter bulunamadı " );
            } else {
                $id      = $_SESSION[ $vt->a( "isim" ) . "userid" ];
                $kontrol = $odb->prepare( "SELECT name FROM player.player WHERE account_id = ? && name = ?" );
                $kontrol->execute( array(
                     $id,
                    $karakter 
                ) );
                if ( $kontrol->rowCount() ) {
                    $kordi  = array(
                         "402100",
                        "673900",
                        "64",
                        "402100",
                        "673900",
                        "64" 
                    );
                    $kurtar = $odb->prepare( "UPDATE player.player SET exit_x = ?, exit_y = ?, exit_map_index = ?, x = ?,y = ?,map_index = ? WHERE name = ?" );
                    $kurtar->execute( array(
                         $kordi[ 0 ],
                        $kordi[ 1 ],
                        $kordi[ 2 ],
                        $kordi[ 3 ],
                        $kordi[ 4 ],
                        $kordi[ 5 ],
                        $karakter 
                    ) );
                    if ( $kurtar->errorInfo()[2] == false  ) {
                        form::basari( $karakter . " Adlı karakteriniz başarıyla bugdan kurtarıldı. 15 dakka boyunca bu karaktere giriş yapmayınız." );
                        $vt->kullanici_log( $karakter . " Karakteri bugdan kurtarıldı" );
                    } else {
                        form::hata( "Sistem hatası" );
                    }
                } else {
                    form::hata( "Karakter size ait değil." );
                }
            }
		
	}
	
}

?>