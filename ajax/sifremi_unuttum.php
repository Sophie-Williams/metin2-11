<?php
	
	class Post_sifremi_unuttum
	{
		
		public static function post()
		{
			
			global $vt, $ayar, $odb, $get;
			
			$variable = gvn::get('variable');
			
			if ($variable == 'sifremiunuttum') {
				$kullanici           = gvn::post('kullanici');
				$email               = gvn::post('email');
				$sifre_unuttum_token = gvn::post('sifre_unuttum_token');
				$captcha_code        = gvn::post('captcha_code');
				if (!$sifre_unuttum_token) {
					form::hata("Token yok");
				} else if ($sifre_unuttum_token != $ayar->sessionid) {
					form::hata("Token Hatası");
				} else if ($_SESSION["captcha_code"] != $captcha_code) {
					form::hata("Güvenlik Kodunu Yanlış Girdiniz");
				} else if (empty($kullanici) || empty($email)) {
					form::hata("Boş alan bırakamazsınız");
				} else {
					$kontrol = $odb->prepare("SELECT login FROM account WHERE login = ? && email = ?");
					$kontrol->execute(array(
						$kullanici,
						$email
					));
					if ($kontrol->rowCount()) {
						$_SESSION["unuttum_kullanici"] = $kullanici;
						$_SESSION["unuttum_email"]     = $email;
						printf('<meta http-equiv="refresh" content="4;URL=sifremi-unuttum?asama=2">');
						form::basari("Bilgileri doğru girdiniz 2.aşamaya yönlendiriliyorsunuz.");
					} else {
						form::hata("Kullanıcı adını veya kullanıcının eposta adresini yanlış girdiniz.");
					}
				}
			} else if ($variable == 'guvenlik') {
				$kullanici_bul = $odb->prepare("SELECT question1, answer1 FROM account WHERE login = ? && email = ?");
				$kullanici_bul->execute(array(
					$_SESSION["unuttum_kullanici"],
					$_SESSION["unuttum_email"]
				));
				
				if ($kullanici_bul->rowCount()) {
					$kullanici = $kullanici_bul->fetch(PDO::FETCH_ASSOC);
                    $guvenlik_token = gvn::post('guvenlik_token');
                    $guvenlik_cevap = gvn::post('guvenlik_cevap');
					
					if (!$guvenlik_token) {
						form::hata("Token yok");
					} else if ($guvenlik_token != $ayar->sessionid) {
						form::hata("Token Hatası");
					} else if ($kullanici['answer1'] != $guvenlik_cevap) {
						form::hata("Güvenlik Sorusunun cevabını yanlış girdiniz..");
					} else {
						$_SESSION["guvenlik_dogru"] = $guvenlik_token;
						form::basari("Güvenlik Sorusu doğru. Şifre değiştirme sayfasına yönlendiriliyorsunuz..");
						$vt->yonlendir('sifremi-unuttum?asama=guvenlik_soru_dogru&token='.$guvenlik_token);
					}
					
				} else {
					form::hata('Kullanıcı bulunamadığı için cevap veremezsiniz');
				}
				
			}
			else if($variable == 'sifre_degistir1'){
				
							$parcala   = explode( "_", $get['token_degistir'] );
							$mail      = $parcala[ 2 ];
							
							if(count($parcala) > 3){
								
								$parcala_mail = "";
								
								for($ii = 2; $ii < count($parcala) - 1; $ii++){
									
									$parcala_mail .= $parcala[$ii].'_';
									
									
								}
								
								$mail = substr($parcala_mail, 0, -1);
								
							}
				
                            $pass              = gvn::post('pass');
                            $pass_retry        = gvn::post('pass_retry');
                            $sifre_degis_token = gvn::post('sifre_degis_token');
                            $captcha_code      = gvn::post('captcha_code');
                            if ( !$sifre_degis_token ) {
                                form::hata( "Token yok" );
                            } else if ( $sifre_degis_token != $ayar->sessionid ) {
                                form::hata( "Token Hatası" );
                            } else if ( !$pass || !$pass_retry ) {
                                form::hata( "Şifrelerinizi boş bırakamazsınız." );
                            } else if ( $pass != $pass_retry ) {
                                form::hata( "Şifreleriniz uyumlu değil" );
                            } else if ( $captcha_code != $_SESSION[ "captcha_code" ] ) {
                                form::hata( "Güvenlik kodunu yanlış girdiniz. ! " );
                            } else {
                                $kontrol = $odb->prepare( "SELECT login FROM account WHERE login = ? && email = ?" );
                                $kontrol->execute( array(
                                     $get['user'],
                                    $mail
                                ) );
                                if ( $kontrol->rowCount() ) {
                                    $guncelle = $odb->prepare( "UPDATE account SET password = PASSWORD(?) WHERE login = ? && email = ?" );
                                    $guncelle->execute( array(
                                         $pass,
										 $get['user'],
										$mail
                                    ) );
                                    if ( $guncelle->errorInfo()[2] == false  ) {
										$vt->yonlendir('giris-yap');
                                        $vt->tokenleri_sil( 7, $get['user'] );
                                        form::basari( "Şifreniz başarıyla değiştirildi.!" );
                                    }
                                } else {
                                    printf( '<meta http-equiv="refresh" content="3;URL=sifremi-unuttum">' );
                                    form::hata( "Böyle Bir kullanıcı bulunamadı." );
                                }
                            }
				
			}
			else if($variable == 'sifre_degistir2'){
				
                        $pass              = gvn::post('pass');
                        $pass_retry        = gvn::post('pass_retry');
                        $sifre_degis_token = gvn::post('sifre_degis_token');
                        $captcha_code      = gvn::post('captcha_code');
                        if ( !$sifre_degis_token ) {
                            form::hata( "Token yok" );
                        } else if ( $sifre_degis_token != $ayar->sessionid ) {
                            form::hata( "Token hatası" );
                        } else if ( !$pass || !$pass_retry ) {
                            form::hata( "Şifrelerinizi boş bırakamazsınız." );
                        } else if ( $pass != $pass_retry ) {
                            form::hata( "Şifreleriniz uyumlu değil" );
                        } else if ( $captcha_code != $_SESSION[ "captcha_code" ] ) {
                            form::hata( "Güvenlik kodunu yanlış girdiniz. ! " );
                        } else {
                            $kontrol = $odb->prepare( "SELECT login FROM account WHERE login = ? && email = ?" );
                            $kontrol->execute( array(
                                 $_SESSION[ "unuttum_kullanici" ],
                                $_SESSION[ "unuttum_email" ] 
                            ) );
                            if ( $kontrol->rowCount() ) {
                                $guncelle = $odb->prepare( "UPDATE account SET password = PASSWORD(?) WHERE login = ? && email = ?" );
                                $guncelle->execute( array(
                                     $pass,
                                    $_SESSION[ "unuttum_kullanici" ],
                                    $_SESSION[ "unuttum_email" ] 
                                ) );
                                if ( $guncelle->errorInfo()[2] == false  ) {
                                    unset( $_SESSION[ "unuttum_email" ] );
                                    unset( $_SESSION[ "unuttum_email" ] );
                                    unset( $_SESSION[ "guvenlik_dogru" ] );
                                    printf( '<meta http-equiv="refresh" content="4;URL=' . $vt->url( 4 ) . '">' );
                                    form::basari( "Şifreniz başarıyla değiştirildi.!" );
                                }
                            } else {
                                printf( '<meta http-equiv="refresh" content="3;URL=sifremi-unuttum">' );
                                form::hata( "Böyle Bir kullanıcı bulunamadı." );
                            }
                        }
				
			}
			
		}
		
	}
	
?>