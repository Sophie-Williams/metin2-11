<?php

class Post_kullaniciadimiunuttum{
	
	public static function post(){
		
		global $vt, $ayar, $odb;
		
                    $email                   = gvn::post('email');
                    $kullanici_unuttum_token = gvn::post('kullanici_unuttum_token');
                    $captcha_code            = gvn::post('captcha_code');
                    if ( !$kullanici_unuttum_token ) {
                        form::hata( "Token yok" );
                    } else if ( $kullanici_unuttum_token != $ayar->sessionid ) {
                        form::hata( "Token hatası" );
                    } else if ( !$email ) {
                        form::hata( "Email Adresini boş bırakamazsınız. !" );
                    } else if ( $captcha_code != $_SESSION[ "captcha_code" ] ) {
                        form::hata( "Güvenlik Kodunu Yanlış Girdiniz" );
                    } else {
                        $kontrol = $odb->prepare( "SELECT email,login FROM account WHERE email = ?" );
                        $kontrol->execute( array(
                             $email 
                        ) );
                        if ( $kontrol->rowCount() ) {
                            if ( $kontrol->rowCount() > 1 ) {
                                $bilgi = array( );
                                while ( $row = $kontrol->fetch( PDO::FETCH_ASSOC ) ) {
                                    $bilgi[ ] = $row[ "login" ];
                                }
                                $bilgi = json_encode( $bilgi );
                            } else {
                                $row   = $kontrol->fetch( PDO::FETCH_ASSOC );
                                $bilgi = $row[ "login" ];
                            }
							$mail_icerik = array('kullanici_adi_unuttum', $bilgi);
						   $mail_gonder = $vt->mail_gonder( $email, "Kullanıcı Adımı Unuttum", $mail_icerik );
                            if ( $mail_gonder ) {
                                form::basari( $email . " adresine kayıtlı tüm kullanıcılar mail adresinize gönderildi . !" );
                                printf( '<meta http-equiv="refresh" content="5;URL=' . $vt->url( 0 ) . '">' );
                            } else {
                                form::hata( "Sistemden kaynaklanan bir hata nedeniyle mail gönderemiyoruz." );
                            }
                        } else {
                            form::hata( "Mail adresine ait kullanıcı bulunamadı" );
                        }
                    }
		
		
	}
	
}

?>