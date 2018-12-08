<?php

class Post_deposifresi{
	
	public static function post(){
		
		global $vt, $ayar, $odb;
		
		$variable = gvn::get('variable');
		
		if($variable == 'sifremail'){
			
		
						$mail_icerik = array('depo_sifre', 1, $_SESSION[ $vt->a( "isim" ) . "username" ], $vt->a( "link" ) . 'kullanici/depo-sifre-degistir?depo_token=' . $ayar->token_rastgele);
                        $gonder      = $vt->mail_gonder( $vt->uye( "email" ), "Depo Şifrenizi Değiştirin", $mail_icerik );
                        if ( !$gonder ) {
                            form::hata( "Sistemdeki hatadan dolayı mail gönderemedik. Yöneticiler bu hata ile ilgileniyor.." );
                        }
						else{
							
                        $vt->token_ekle( 5, $_SESSION[ $vt->a( "isim" ) . "username" ], $ayar->token_rastgele );
							
                        $vt->kullanici_log( "Depo şifre değiştirme isteği gönderildi." );

						form::basari('Email adresinize gelen linke tıklayarak depo şifrenizi değiştirin.');
												
						}
						
		}
		else if($variable == 'sifredegismail'){
			
                                    $pass_depo         = gvn::post('pass_depo');
                                    $pass_depo_retry   = gvn::post('pass_depo_retry');
                                    $depo_captcha_code = gvn::post('depo_captcha_code');
                                    $sifre_degis_token = gvn::post('sifre_degis_token');
                                    if ( !$sifre_degis_token ) {
                                        form::hata( 'Token yok' );
                                    } else if ( $ayar->sessionid != $sifre_degis_token ) {
                                        form::hata( 'Token Hatası' );
                                    } else if ( strlen( $pass_depo ) != 6 ) {
                                        form::hata( "Depo şifreniz 6 haneli ve sadece sayı olmalıdır." );
                                    } else if ( !$pass_depo || $pass_depo != $pass_depo_retry ) {
                                        form::hata( "Şifreleriniz uyumlu değil" );
                                    } else if ( $_SESSION[ "captcha_code" ] != $depo_captcha_code ) {
                                        form::hata( "Güvenlik Kodunu Yanlış Girdiniz" );
                                    } else {
                                        $guncelle = $odb->prepare( "UPDATE player.safebox SET password = ? WHERE account_id = ?" );
                                        $guncelle->execute( array(
                                             $pass_depo,
                                            $_SESSION[ $vt->a( "isim" ) . "userid" ] 
                                        ) );
                                        if ( $guncelle ) {
                                            $vt->tokenleri_sil( 5, $_SESSION[ $vt->a( "isim" ) . "username" ] );
                                            printf( '<meta http-equiv="refresh" content="4;URL=kullanici">' );
                                            $vt->kullanici_log( "Depo şifresi değiştirildi" );
                                            form::basari( "Depo şifreniz başarıyla değiştirildi." );
                                        } else {
                                            form::hata( "Sistem hatası" );
                                        }
                                    }
			
		}
		else if($variable == 'sistemdegis'){
                        $rastgele_sifre = substr( str_shuffle( "1234567890" ), 0, 6 );
                        $guncelle       = $odb->prepare( "UPDATE player.safebox SET password = ? WHERE account_id = ?" );
                        $guncelle->execute( array(
                             $rastgele_sifre,
                            $_SESSION[ $vt->a( "isim" ) . "userid" ] 
                        ) );
                        if ( $guncelle->errorInfo()[2] == false  ) {
							$mail_icerik = array('depo_sifre', 2, $_SESSION[ $vt->a( "isim" ) . "username" ], $rastgele_sifre );							
                            $gonder      = $vt->mail_gonder( $vt->uye( "email" ), "Depo Şifreniz Değiştirildi", $mail_icerik );
                            if ( !$gonder ) {
                                form::hata( "Sistemdeki hatadan dolayı mail gönderemedik. Yöneticiler bu hata ile ilgileniyor.." );
                            }
							else{
                            $vt->kullanici_log( "Depo şifresi değiştirildi" );
                            form::basari( "Yeni Depo şifreniz " . $vt->uye( "email" ) . " Adresine gönderildi. " );
                            printf( '<meta http-equiv="refresh" content="4;URL=kullanici">' );
							}
                        } else {
                            form::hata( "Sistem hatası" );
                        }
		}
		else if($variable == 'direkdegis'){
			
                    $pass              = gvn::post('pass');
                    $pass_retry        = gvn::post('pass_retry');
                    $sifre_degis_token = gvn::post('sifre_degis_token');
                    $captcha_code      = gvn::post('captcha_code');
                    if ( !$sifre_degis_token ) {
                        form::hata( "Token yok" );
                    } else if ( $ayar->sessionid != $sifre_degis_token ) {
                        form::hata( "Token hatası" );
                    } else if ( strlen( $pass ) != 6 ) {
                        form::hata( "Depo şifreniz 6 haneli ve sadece sayı olmalıdır." );
                    } else if ( !$pass || $pass != $pass_retry ) {
                        form::hata( "Depo şifreniz 6 haneli ve sadece sayı olmalıdır." );
                    } else if ( $_SESSION[ "captcha_code" ] != $captcha_code ) {
                        form::hata( "Güvenlik Kodunu Yanlış Girdiniz" );
                    } else {
                        $guncelle = $odb->prepare( "UPDATE player.safebox SET password = ?  WHERE account_id = ?" );
                        $guncelle->execute( array(
                             $pass,
                            $_SESSION[ $vt->a( "isim" ) . "userid" ] 
                        ) );
                        if ( $guncelle->errorInfo()[2] == false  ) {
                            $vt->kullanici_log( "Depo şifresi değiştirildi" );
                            form::basari( "Depo Şifrenizi Başarıyla Değiştirdiniz." );
                            printf( '<meta http-equiv="refresh" content="4;URL=kullanici">' );
                        } else {
                            form::hata( "Sistem hatası" );
                        }
                    }
			
		}
		
	}
	
}

?>