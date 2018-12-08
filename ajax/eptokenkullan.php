<?php
	
	class Post_eptokenkullan
	{
		
		public static function post()
		{
			
			global $vt, $ayar, $odb, $get, $db;
			
                $token        = gvn::post('token');
                $crsf_token   = gvn::post('crsf_token');
                $captcha_code = gvn::post('captcha_code');
                if ( !$crsf_token ) {
                    form::hata( "Token Yok" );
                } else if ( $ayar->sessionid != $crsf_token ) {
                    form::hata( "Token Hatası" );
                } else if ( $_SESSION[ "captcha_code" ] != $captcha_code ) {
                    form::hata( "Güvenlik Kodunu Yanlış Girdiniz" );
                } else {
                    $kontrol = $db->prepare( "SELECT id,ep,kullanan FROM eptoken WHERE sid = ? && token = ?" );
                    $kontrol->execute( array(
                         server,
                        $token
                    ) );
                    if ( $kontrol->rowCount() ) {
                        $fetch = $kontrol->fetch( PDO::FETCH_ASSOC );
                        if ( $fetch[ "kullanan" ] == "" ) {
                            $token_kullan   = $db->prepare( "UPDATE eptoken SET kullanan = ?, kullanma_tarih = ? WHERE sid = ? && id = ?" );
                            $token_kullan->execute( array(
                                 $_SESSION[ $vt->a( "isim" ) . "username" ],
                                date( "Y-m-d H:i:s" ),
                                server,
                                $fetch[ "id" ] 
                            ) );
                            if ( $token_kullan->errorInfo()[2] == false ) {
                                $ep_gonder = $odb->prepare( "UPDATE account SET coins = coins + ? WHERE id = ? && login = ? " );
                                $ep_gonder->execute( array(
                                     $fetch[ "ep" ],
                                    $_SESSION[ $vt->a( "isim" ) . "userid" ],
                                    $_SESSION[ $vt->a( "isim" ) . "username" ] 
                                ) );
                                if ( $ep_gonder->errorInfo()[2] == false ) {
                                    form::basari( "Ep tokeni başarıyla kullanıldı.  " . $fetch["ep"] . " Ejderha parası hesabınıza yüklendi" );
                                } else {
                                    form::uyari( "Ep tokeni kullanıldı fakat barındırdığı ep hesabınıza yüklenemedi. Bu hata yöneticiye bildirildi" );
                                    form::hata_gonder( $_SESSION[ $vt->a( "isim" ) . "username" ] . " adlı kullanıcıya ep tokeninden " . $fetch[ "ep" ] . " Yüklenemedi" );
                                }
                            } else {
                                form::hata( "Sistem hatası" );
                            }
                        } else {
                            form::hata( "Bu Token Zaten Kullanılmış" );
                        }
                    } else {
                        form::hata( "Böyle bir token bulunamadı" );
                    }
                }
			
		}
		
	}
	
?>