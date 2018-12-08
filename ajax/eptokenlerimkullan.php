<?php
	
	class Post_eptokenlerimkullan
	{
		
		public static function post()
		{
			
			global $vt, $ayar, $odb, $get, $db;
			
                    $id = gvn::get('variable');
                    $kontrol_token = $db->prepare( "SELECT id,kullanan,ep FROM eptoken WHERE sid = ? && id = ? && olusturan = ? " );
                    $kontrol_token->execute( array(
                         server,
                        $id,
                        $_SESSION[ $vt->a( "isim" ) . "username" ] 
                    ) );
                    if ( $kontrol_token->rowCount() ) {
                        $fetch = $kontrol_token->fetch( PDO::FETCH_ASSOC );
                        if ( $fetch[ "kullanan" ] == "" || !$fetch[ "kullanan" ] ) {
                            $token_kullan   = $db->prepare( "UPDATE eptoken SET kullanan = ?, kullanma_tarih = ? WHERE sid = ? && id = ?" );
                            $token_kullan->execute( array(
                                 $_SESSION[ $vt->a( "isim" ) . "username" ],
                                date( "Y-m-d H:i:s" ),
                                server,
                                $id
                            ) );
                            if ( $token_kullan->errorInfo()[2] == false ) {
                                $ep_gonder = $odb->prepare( "UPDATE account SET coins = coins + ? WHERE id = ? && login = ? " );
                                $ep_gonder->execute( array(
                                     $fetch[ "ep" ],
                                    $_SESSION[ $vt->a( "isim" ) . "userid" ],
                                    $_SESSION[ $vt->a( "isim" ) . "username" ] 
                                ) );
                                if ( $ep_gonder ) {
                                    form::basari( "Ep tokeni başarıyla kullanıldı. Barındırdığı ep hesabınıza yüklendi" );
                                } else {
                                    form::uyari( "Ep tokeni kullanıldı fakat barındırdığı ep hesabınıza yüklenemedi. Bu hata yöneticiye bildirildi" );
                                    form::hata_gonder( $_SESSION[ $vt->a( "isim" ) . "username" ] . " adlı kullanıcıya ep tokeninden " . $fetch[ "ep" ] . " Yüklenemedi" );
                                }
                            } else {
                                form::hata( "Sistem hatası" );
                            }
                        } else {
                            form::hata( "Bu ep tokeni zaten kullanılmış" );
                        }
                    } else {
                        form::hata( "Böyle bir ep bulunamadı" );
                    }
			
		}
		
	}
	
?>