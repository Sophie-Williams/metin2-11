<?php
	
	class Post_epgonder
	{
		
		public static function post()
		{
			
			global $vt, $ayar, $odb, $get, $db;
			
					$eptransfer = explode( ',', $vt->a( "eptransfer" ) );
			
                    $gonderilcek = gvn::post('gonderilcek');
                    $epmiktar    = gvn::post('epmiktar');
                    if ( $eptransfer[ 1 ] == 1 ) {
                        $epass = gvn::post('epass');
                    }
                    $crsf_token   = gvn::post('crsf_token');
                    $captcha_code = gvn::post('captcha_code');
                    if ( !$crsf_token ) {
                        form::hata( "Token Yok" );
                    } else if ( $ayar->sessionid != $crsf_token ) {
                        form::hata( "Token Hatası" );
                    } else if ( $_SESSION[ "captcha_code" ] != $captcha_code ) {
                        form::hata( "Güvenlik Kodunu Yanlış Girdiniz" );
                    } else if ( $eptransfer[ 1 ] == 1 && @$epass == "" ) {
                        form::hata( "Ep transfer şifresi boş bırakılamaz" );
                    } else {
                        $kullanici_kontrol = $odb->prepare( "SELECT account.login, account.id, account.edurum FROM player.player LEFT JOIN account.account ON player.account_id = account.id WHERE player.account_id != ? && player.name = ?" );
                        $kullanici_kontrol->execute( array(
                             $_SESSION[ $vt->a( "isim" ) . "userid" ],
                            $gonderilcek 
                        ) );
                        if ( $kullanici_kontrol->rowCount() ) {
                            $fetch = $kullanici_kontrol->fetch( PDO::FETCH_ASSOC );
                            if ( $eptransfer[ 1 ] == 1 && $vt->uye( "epass" ) != $epass ) {
                                form::hata( "Ep transfer şifrenizi yanlış girdiniz" );
                            } else if ( $fetch[ "edurum" ] == 2 ) {
                                form::hata( "Karşıdaki karakter ep transfer sistemini kabul etmediği için ep gönderemiyorsunuz" );
                            } else if ( $vt->uye( "coins" ) < $epmiktar ) {
                                form::hata( "Epiniz yeterli olmadığı için gönderemezsiniz" );
                            } else {
                                $kendi_ep_dusur = $odb->prepare( "UPDATE account SET coins = coins - ? WHERE id = ? && login = ?" );
                                $kendi_ep_dusur->execute( array(
                                     $epmiktar,
                                    $_SESSION[ $vt->a( "isim" ) . "userid" ],
                                    $_SESSION[ $vt->a( "isim" ) . "username" ] 
                                ) );
                                if ( $kendi_ep_dusur->errorInfo()[2] == false  ) {
                                    $log_send       = $db->prepare( "INSERT INTO eptransfer_log SET sid = ?, tur = ?, gonderen = ?, alan = ?, ep = ?, tarih = ?" );
                                    $log_gonder     = $log_send->execute( array(
                                         server,
                                        1,
                                        $_SESSION[ $vt->a( "isim" ) . "username" ],
                                        $gonderilcek,
                                        $epmiktar,
                                        date( "Y-m-d H:i:s" ) 
                                    ) );
                                    $karsi_ep_yukle = $odb->prepare( "UPDATE account SET coins = coins + ? WHERE id = ? && login = ?" );
                                    $karsi_ep_yukle->execute( array(
                                         $epmiktar,
                                        $fetch[ "id" ],
                                        $fetch[ "login" ] 
                                    ) );
                                    if ( $karsi_ep_yukle->errorInfo()[2] == false  ) {
                                        form::basari( $gonderilcek . " Adlı karaktere " . $epmiktar . " EP Gönderdiniz" );
                                    } else {
                                        $vt->hata_gonder( $_SESSION[ $vt->a( "isim" ) . "username" ] . " Adlı kullanıcı " . $gonderilcek . " Adlı Karaktere " . $epmiktar . " Ep Gönderirken bir hata meydana geldi. (Kullanıcıdan ep eksildi)" );
                                        form::uyari( "Epiniz eksildi fakat " . $gonderilcek . " Adlı karaktere " . $epmiktar . " EP Gönderilemedi. Bu hata yöneticiye bildirildi" );
                                    }
                                } else {
                                    form::hata( "Sistem hatası" );
                                }
                            }
                        } else {
                            form::hata( "Böyle bir karakter bulunamadı" );
                        }
                    }
			
			
		}
		
	}
	
?>