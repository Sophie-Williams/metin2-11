<?php
	
	class Post_epgonderSifredegistir
	{
		
		public static function post()
		{
			
			global $vt, $ayar, $odb, $get, $db;
			
                    $old_pass     = gvn::post('old_pass');
                    $pass         = gvn::post('pass');
                    $pass_retry   = gvn::post('pass_retry');
                    $crsf_token   = gvn::post('crsf_token');
                    $captcha_code = gvn::post('captcha_code');
                    if ( !$crsf_token ) {
                        form::hata( "Token Yok" );
                    } else if ( $ayar->sessionid != $crsf_token ) {
                        form::hata( "Token Hatası" );
                    } else if ( $_SESSION[ "captcha_code" ] != $captcha_code ) {
                        form::hata( "Güvenlik Kodunu Yanlış Girdiniz" );
                    } else if ( $old_pass != $vt->uye( "epass" ) ) {
                        form::hata( "Eski şifrenizi yanlış girdiniz" );
                    } else if ( $pass != $pass_retry || !$pass ) {
                        form::hata( "Şifreleriniz aynı değil" );
                    } else {
                        $guncelle   = $odb->prepare( "UPDATE account SET epass = ? WHERE id = ? && login = ?" );
                        $guncelle->execute( array(
                             $pass,
                            $_SESSION[ $vt->a( "isim" ) . "userid" ],
                            $_SESSION[ $vt->a( "isim" ) . "username" ] 
                        ) );
                        if ( $guncelle->errorInfo()[2] == false ) {
                            $log_send   = $db->prepare( "INSERT INTO eptransfer_log SET sid = ?, tur = ?, gonderen = ?, tarih = ?" );
                            $log_gonder = $log_send->execute( array(
                                 server,
                                3,
                                $_SESSION[ $vt->a( "isim" ) . "username" ],
                                date( "Y-m-d H:i:s" ) 
                            ) );
                            form::basari( "Ep transfer şifreniz başarıyla değiştirildi" );
                        } else {
                            form::hata( "Sistem hatası" );
                        }
                    }
			
		}
		
	}
	
?>