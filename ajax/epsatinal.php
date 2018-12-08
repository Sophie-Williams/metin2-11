<?php
	
	class Post_epsatinal
	{
		
		public static function post()
		{
			
			global $vt, $ayar, $odb, $get, $db;
			
            $kontrol = $db->prepare( "SELECT id,fiyat,ep FROM epfiyatlari WHERE sid = ? && id = ?" );
            $kontrol->execute( array(
                 server,
                 gvn::get('variable')
            ) );
            if ( $kontrol->rowCount() ) {
				
				@$kontrol2 = $odb->prepare( "SELECT bakiye FROM account LIMIT 1" );
				@$kontrol2->execute( );
                
				if ( $kontrol2->errorInfo()[2] == false ) {
                    @$kontrol_token = $db->prepare( "SELECT id FROM eptoken" );
                    @$kontrol_token->execute();
                    if ( $kontrol_token->errorInfo()[2] == false ) {
                        $fetch = $kontrol->fetch( PDO::FETCH_ASSOC );
                        @$kontrol_hata = $db->prepare( "SELECT id FROM hatalar WHERE tur = ? && sid = ? && kullanici = ?" );
                        @$kontrol_hata->execute( array(
                             1,
                            server,
                            $_SESSION[ $vt->a( "isim" ) . "username" ] 
                        ) );
                        if ( $vt->uye( "bakiye" ) < $fetch[ "fiyat" ] ) {
                            form::hata( "Bakiyeniz yeterli değil" );
                        } else if ( $kontrol_hata->rowCount() || $kontrol_hata->errorInfo()[2] != false ) {
                            form::hata( "Hatalar listesinde isminiz var. Hata giderilene kadar alışveriş yapamazsınız" );
                        } else {
							$random1 = "";
							for($i = 0; $i <= 4; $i++){
								$random1 .= substr( str_shuffle( "ABCDEFGHJKLMNOPRSTUVYZWQ" ), 0, 5 )."-";
							}
                            $random1       = substr( $random1, 0, -1 );
                            $token_olustur  = $db->prepare( "INSERT INTO eptoken SET sid = ?, token = ?, ep = ?, olusturan = ?, olusturma_tarih = ?" );
                            $token_olustur->execute( array(
                                 server,
                                $random1,
                                $fetch[ "ep" ],
                                $_SESSION[ $vt->a( "isim" ) . "username" ],
                                date( "Y-m-d H:i:s" ) 
                            ) );
                            $ayarlar       = explode( ',', $vt->a( "eptoken" ) );
                            if ( $token_olustur->errorInfo()[2] == false ) {
                                if ( $ayarlar[ 0 ] == 1 ) {
									$mail_icerik = array('ep_token', 1, $_SESSION[ $vt->a( "isim" ) . "username"], $random1, $fetch["fiyat"], $fetch["ep"]);
                                    $mail_gonder = $vt->mail_gonder( $vt->uye( "email" ), "Satın Alınan Ep Bilgileri", $mail_icerik );
                                    if ( !$mail_gonder ) {
                                        form::hata( "Mail Gönderilemedi" );
                                    }
                                    $bilgi = "Ep başarıyla satın alındı. Bilgiler Mail Adresinize Gönderildi Oluşturduğunuz epi . Ep tokenlerimden görebilirsiniz";
                                } else {
                                    $bilgi = "Ep başarıyla satın alındı. Oluşturduğunuz epi . Ep tokenlerimden görebilirsiniz";
                                }
                                $bakiye_dusur = $odb->prepare( "UPDATE account SET bakiye = bakiye - ? WHERE id = ? && login = ?" );
                                $bakiye_dusur->execute( array(
                                     $fetch[ "fiyat" ],
                                    $_SESSION[ $vt->a( "isim" ) . "userid" ],
                                    $_SESSION[ $vt->a( "isim" ) . "username" ] 
                                ) );
                                if ( $bakiye_dusur->errorInfo()[2] == false ) {
                                    form::basari( $bilgi );
                                } else {
                                    $vt->hata_gonder( $_SESSION[ $vt->a( "isim" ) . "username" ] . " Adlı kullanıcı " . $fetch[ "fiyat" ] . " TL Ye ep oluşturdu fakat bakiyesi eksilmedi", 1, $_SESSION[ $vt->a( "isim" ) . "username" ] );
                                    form::uyari( "Ep tokeni başarıyla oluşturuldu fakat bakiyeniz eksilmedi. Bu hata admine bildirildi . Hata giderilene kadar daha ep satın alamazsınız. ! " );
                                }
                            } else {
                                form::hata( "Sistem hatası" );
                            }
                        }
                    } else {
                        form::hata( "Ep oluşturma sistemi kurulu değil" );
                    }
                } else {
                    form::hata( "Bakiye sistemi kurulu değil" );
                }
            } else {
                form::hata( "Böyle Bir Ep Fiyatı Bulunamadı" );
            }
			
		}
		
	}
	
?>