<?php

class Post_kullaniciadidegistir{
	
	public static function post(){
		
		global $vt, $ayar, $odb;
				
			if($vt->a( "kullanici_degis" ) != 3 ){
		
                    $kullanici_degis_token = gvn::post('kullanici_degis_token');
                    $yeni_kullanici        = gvn::post('yeni_kullanici');
                    $kontrol               = $odb->prepare( "SELECT login FROM account WHERE login = ?" );
                    $kontrol->execute( array(
                         $yeni_kullanici 
                    ) );
                    if ( strlen( $yeni_kullanici ) < 5 || strlen( $yeni_kullanici ) > 15 ) {
                        form::hata( "Yeni kullanıcı adınız en az 5 en fazla 15 karakterden oluşmalıdır. !" );
                    } else if ( $_SESSION[ $vt->a( "isim" ) . "username" ] == $yeni_kullanici ) {
                        form::hata( "Kullanıcı adınız zaten sistemde " . $yeni_kullanici . " olarak kayıtlı. !" );
                    } else if ( $kontrol->rowCount() ) {
                        form::hata( $yeni_kullanici . " adında bir kullanıcı sistemde zaten kayıtlı. !" );
                    } else if ( $vt->a( "kullanici_degis" ) == 2 ) {
                        $vt->token_ekle( 4, $_SESSION[ $vt->a( "isim" ) . "username" ], $ayar->token_rastgele . '_' . $yeni_kullanici );
						$mail_icerik = array('kullanici_adi_degistir', $_SESSION[ $vt->a( "isim" ) . "username" ], $yeni_kullanici,
						$vt->a("link") . 'kullanici/kullanici-adi-degistir?token='. $ayar->token_rastgele . '_' . $yeni_kullanici . '&user=' . $_SESSION[ $vt->a( "isim" ) . "username" ]);
                        $gonder      = $vt->mail_gonder( $vt->uye( "email" ), "Kullanıcı Adınız Değiştirilecek", $mail_icerik );
                        if ( !$gonder ) {
                            form::hata( "Sistemdeki hatadan dolayı mail gönderemedik. Yöneticiler bu hata ile ilgileniyor.." );
                        }
                        $vt->kullanici_log( "Kullanıcı adı değiştirme isteği yollandı" );
                        form::basari( $vt->uye( "email" ) . " Adresine kullanıcı adı değiştirme talebi gönderildi." );
                    } else {
                        $guncelle = $odb->prepare( "UPDATE account SET login = ? WHERE login = ? && id = ?" );
                        $guncelle->execute( array(
                             $yeni_kullanici,
                            $_SESSION[ $vt->a( "isim" ) . "username" ],
                            $_SESSION[ $vt->a( "isim" ) . "userid" ] 
                        ) );
                        if ( $guncelle->errorInfo()[2] == false ) {
                            $vt->kullanici_log( "Kullanıcı adı değiştirildi" );
                            session_destroy();
                            printf( '<meta http-equiv="refresh" content="2;URL=giris-yap">' );
                            form::basari( "Kullanıcı Adınız Başarıyla " . $yeni_kullanici . " olarak değiştirilmiştir. Çıkış yapılıyor." );
                        } else {
                            form::hata( "Sistem hatası" );
                        }
                    }
					
			}
			else{
				form::hata('Kullanıcı adınızı değiştiremezsiniz');
			}
		
	}
	
}

?>