<?php

class Post_karaktersilmesifre{
	
	public static function post(){
		
		global $vt, $ayar, $odb;
		

		$variable = gvn::get('variable');
		
		if($variable == 'mailgonder'){
	 
						$token = $ayar->token_rastgele;
	 
						$mail_icerik = array('karakter_silme_sifre', 1, $_SESSION[ $vt->a( "isim" ) . "username" ], 
						$vt->a( "link" ) . 'kullanici/karakter-silme-sifresi-degistir?karakter_token=' . $token);
                        $gonder      = $vt->mail_gonder( $vt->uye( "email" ), "Karakter Silme Şifrenizi Değiştirin", $mail_icerik );
                        if ( !$gonder ) {
                            form::hata( "Sistemdeki hatadan dolayı mail gönderemedik. Yöneticiler bu hata ile ilgileniyor.." );
                        }
						else{
                        $vt->token_ekle( 6, $_SESSION[ $vt->a( "isim" ) . "username" ], $token );
                        $vt->kullanici_log( "Karakter silme şifresini değiştirme isteği yollandı" );
						form::basari('Email adresinize gelen linke tıklayarak karakter silme şifrenizi değiştirin.');
						}
					
		}
		else if($variable == 'mailsifredegis'){
			
                                    $pass_karakter         = gvn::post('pass_karakter');
                                    $pass_karakter_retry   = gvn::post('pass_karakter_retry');
                                    $karakter_captcha_code = gvn::post('karakter_captcha_code');
                                    $sifre_degis_token     = gvn::post('sifre_degis_token');
                                    if ( !$sifre_degis_token ) {
                                        form::hata( "Token yok" );
                                    } else if ( $ayar->sessionid != $sifre_degis_token ) {
                                        form::hata( "Token hatası" );
                                    } else if ( strlen( $pass_karakter ) != 7 ) {
                                        form::hata( "Karakter Silme şifreniz 7 haneli ve sadece sayı olmalıdır." );
                                    } else if ( !$pass_karakter || $pass_karakter != $pass_karakter_retry ) {
                                        form::hata( "Şifreleriniz uyumlu değil" );
                                    } else if ( $_SESSION[ "captcha_code" ] != $karakter_captcha_code ) {
                                        form::hata( " Güvenlik Kodunu Yanlış Girdiniz" );
                                    } else {
                                        $guncelle = $odb->prepare( "UPDATE account SET social_id = ?  WHERE login = ?  && id = ?" );
                                        $guncelle->execute( array(
                                             $pass_karakter,
                                            $_SESSION[ $vt->a( "isim" ) . "username" ],
                                            $_SESSION[ $vt->a( "isim" ) . "userid" ] 
                                        ) );
                                        if ( $guncelle->errorInfo()[2] == false ) {
                                            $vt->tokenleri_sil( 6, $_SESSION[ $vt->a( "isim" ) . "username" ] );
                                            printf( '<meta http-equiv="refresh" content="4;URL=' . $vt->url( 5 ) . '">' );
                                            $vt->kullanici_log( "Karakter Silme şifresi değiştirildi" );
                                            form::basari( "Karakter Silme şifreniz başarıyla değiştirildi." );
                                        } else {
                                            form::hata( "Sistem hatası" );
                                        }
                                    }
			
		}
		else if($variable == 'direkdegis'){
			
                    $pass              = gvn::post('pass');
                    $pass_retry        = gvn::post('pass_retry');
                    $sifre_degis_token = gvn::post('sifre_degis_token');
                    $captcha_code      = gvn::post('captcha_code');
                    if ( !$sifre_degis_token ) {
                        echo 'Token yok';
                    } else if ( $ayar->sessionid != $sifre_degis_token ) {
                        echo 'Token Hatası';
                    } else if ( strlen( $pass ) != 7 ) {
                        form::hata( "Karakter Silme şifreniz 7 haneli ve sadece sayı olmalıdır." );
                    } else if ( !$pass || $pass != $pass_retry ) {
                        form::hata( "Şifreleriniz uyumlu değil" );
                    } else if ( $_SESSION[ "captcha_code" ] != $captcha_code ) {
                        form::hata( "Güvenlik Kodunu Yanlış Girdiniz" );
                    } else {
                        $guncelle = $odb->prepare( "UPDATE account SET social_id = ? WHERE login = ?  && id = ?" );
                        $guncelle->execute( array(
                             $pass,
                            $_SESSION[ $vt->a( "isim" ) . "username" ],
                            $_SESSION[ $vt->a( "isim" ) . "userid" ] 
                        ) );
                        if ( $guncelle->errorInfo()[2] == false ) {
                            printf( '<meta http-equiv="refresh" content="4;URL=' . $vt->url( 5 ) . '">' );
                            $vt->kullanici_log( "Karakter Silme şifresi değiştirildi" );
                            form::basari( "Karakter Silme Şifrenizi Başarıyla Değiştirdiniz." );
                        } else {
                            form::hata( "Sistem hatası" );
                        }
                    }
			
		}
		else if($variable == 'sistemdegis'){
			
                        $rastgele_sifre = substr( str_shuffle( "1234567890" ), 0, 7 );
                        $guncelle       = $odb->prepare( "UPDATE account SET social_id = ?  WHERE login = ?  && id = ?" );
                        $guncelle->execute( array(
                             $rastgele_sifre,
                            $_SESSION[ $vt->a( "isim" ) . "username" ],
                            $_SESSION[ $vt->a( "isim" ) . "userid" ] 
                        ) );
                        if ( $guncelle->errorInfo()[2] == false ) {
							$mail_icerik = array('karakter_silme_sifre', 2, $_SESSION[ $vt->a( "isim" ) . "username" ], $rastgele_sifre );							
                            $gonder      = $vt->mail_gonder( $vt->uye( "email" ), "Karakter Silme Şifreniz Değiştirildi", $mail_icerik );
                            if ( !$gonder ) {
                                form::hata( "Sistemdeki hatadan dolayı mail gönderemedik. Yöneticiler bu hata ile ilgileniyor.." );
                            }
							else{
                            printf( '<meta http-equiv="refresh" content="4;URL=' . $vt->url( 5 ) . '">' );
                            $vt->kullanici_log( "Karakter Silme şifresi değiştirildi" );
                            form::basari( "Yeni Karakter Silme şifreniz email adresinize gönderildi. " );
							}
                        } else {
                            form::hata( "Sistem hatası" );
                        }
		}
		
		
		
	}
	
}

?>