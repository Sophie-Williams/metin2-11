<?php
	
	class Post_epgonderSifremiunuttum
	{
		
		public static function post()
		{
			
			global $vt, $ayar, $odb, $get, $db;
			
                    $random   = substr( str_shuffle( "abcdefghkl0123456789" ), 0, 7 );
                    $guncelle   = $odb->prepare( "UPDATE account SET epass = ? WHERE id = ? && login = ?" );
                    $guncelle->execute( array(
                         $random,
                        $_SESSION[ $vt->a( "isim" ) . "userid" ],
                        $_SESSION[ $vt->a( "isim" ) . "username" ] 
                    ) );
                    if ( $guncelle->errorInfo()[2] == false ) {
                        $log_send    = $db->prepare( "INSERT INTO eptransfer_log SET sid = ?, tur = ?, gonderen = ?, tarih = ?" );
                        $log_gonder  = $log_send->execute( array(
                             server,
                            2,
                            $_SESSION[ $vt->a( "isim" ) . "username" ],
                            date( "Y-m-d H:i:s" ) 
                        ) );
							$mail_icerik = array('ep_transfer_sifre_unuttum', $_SESSION[ $vt->a( "isim" ) . "username" ], $random );							
                        $gonder      = $vt->mail_gonder( $vt->uye( "email" ), "Ep Transfer Şifresi Değiştirildi", $mail_icerik );
                        if ( $gonder ) {
                            form::basari( "Yeni ep transfer şifreniz mailinize gönderildi." );
                        } else {
                            form::uyari( "Şifreniz Değiştirildi. Fakat mail gönderemedik. Bu hatayı en kısa süre içerisinde düzelteceğiz." );
                        }
                    } else {
                        form::hata( "Sistem hatası" );
                    }
		}
		
	}
	
?>