<?php

class Post_kaydol{
	
	public static function post(){
		
		global $vt, $ayar, $odb;
		
		
		
                if ( $_POST ) {
                    $real_name         = gvn::post('real_name');
                    $username          = gvn::post('username');
                    $pass              = gvn::post('pass');
                    $pass_retry        = gvn::post('pass_retry');
                    $social_id         = gvn::post('social_id');
                    $phone_number      = gvn::post('phone_number');
                    $eposta            = gvn::post('eposta');
                    $kayit_token       = gvn::post('kayit_token');
                    $captcha_code      = gvn::post('captcha_code');
					$davete_yes        = gvn::post('davet');
                    $guvenlik_sorulari = array(
                         0 => "",
                        1 => "En iyi arkadasim",
                        2 => "Dogum yerim",
                        3 => "Dedemin meslegi",
                        4 => "Favori itemim",
                        5 => "En sevdigim sehir" 
                    );
                    if ( $vt->a( "guvenlik" ) == 1 ) {
                        $guvenlik_soru  = gvn::post('guvenlik_soru');
                        $guvenlik_cevap = gvn::post('guvenlik_cevap');
                    } else {
                        $guvenlik_soru  = 0;
                        $guvenlik_cevap = "";
                    }
                    if ( $vt->a( "mail_kac" ) == 1 ) {
                        $kontrol_mail = $odb->prepare( "SELECT id FROM account WHERE email = ? LIMIT 1" );
                        $kontrol_mail->execute( array(
                             $eposta
                        ) );
                        if ( $kontrol_mail->rowCount() ) {
                            $mail_var = true;
                        }
                    }
                    $expire  = "2190 days";
                    $expirex = date( "Y-m-d H:i:s", strtotime( $expire ) );
                    $token   = $ayar->token_rastgele;
                    $veriler = array(
                         $username,
                        $real_name,
                        $social_id,
                        $eposta,
                        date( "Y-m-d H:i:s" ),
                        $expirex,
                        $expirex,
                        $expirex,
                        $expirex,
                        $expirex,
                        $expirex,
                        $phone_number,
                        $pass,
                        0,
                        $guvenlik_sorulari[ $guvenlik_soru ],
                        $guvenlik_cevap,
                        $_SERVER[ "REMOTE_ADDR" ],
                        $davete_yes 
                    );
                    if ( isset( $sistem_true ) ) {
                        @$epass = $WMkontrol->WM_post( $WMkontrol->WM_html( $_POST[ "epass" ] ) );
                        if ( isset( $_POST[ "eptransfer" ] ) ) {
                            $edurum = 2;
                        } else {
                            $edurum = 1;
                        }
                        @$kontrol_sistem = $odb->prepare( "SELECT edurum, epass FROM account LIMIT 1" );
                        @$kontrol_sistem->execute( );
                        if ( $kontrol_sistem && @$eptransfer[ 1 ] == 1 ) {
                            $sutun1 = ", edurum = ?, epass = ?";
                            array_push( $veriler, $edurum, $epass );
                        } else if ( $kontrol_sistem && @$eptransfer[ 1 ] == 2 ) {
                            $sutun1 = ", edurum = ?";
                            array_push( $veriler, $edurum );
                        } else {
                            $sutun1 = "";
                        }
                    } else {
                        $sutun1 = "";
                        $value1 = "";
                    }
                    $error = array( );
                    try {
                        if ( !$kayit_token ) {
                            throw new Exception( "Token bulunamadı" );
                        } else if ( $ayar->sessionid != $kayit_token ) {
                            throw new Exception( "Token uyumsuzluğu" );
                        } else if ( $captcha_code != $_SESSION[ "captcha_code" ] ) {
                            throw new Exception( 'Güvenlik kodunu yanlış girdiniz' );
                        } else if ( !$username || !$real_name || !$pass || !$social_id || !$phone_number || !$eposta ) {
                            throw new Exception( '* İle gösterilen yerleri boş bırakamazsınız' );
                        } else if ( isset( $sistem_true ) && @$eptransfer[ 0 ] == 1 && @!$epass && @$edurum == 1 && @$eptransfer[ 1 ] == 1 ) {
                            throw new Exception( 'Ep transfer şifresini boş bırakamazsınız' );
                        } else if ( strlen( $username ) < 5 || strlen( $username ) > 15 ) {
                            throw new Exception( 'Kullanıcı Adınızın uzunluğu en az 5 en fazla 15 karakterden oluşabilir.' );
                        } else if ( strlen( $social_id ) != 7 ) {
                            throw new Exception( 'Karakter Silme Şifreniz 7 haneli olmak zorundadır. ' );
                        } else if ( gvn::eposta($eposta) == false ) {
                            throw new Exception( 'E-posta Adresiniz test@ornek.com şeklinde olmalıdır.' );
                        } else if ( isset( $mail_var ) ) {
                            throw new Exception( 'Bu mail ile daha önce kayıt olunmuş' );
                        } else if ( ( $vt->a( "guvenlik" ) ) == 1 && ( !$guvenlik_soru || !$guvenlik_cevap ) ) {
                            throw new Exception( 'Güvenlik sorusunu ve cevabını boş bırakamazsınız....' );
                        } else if ( $pass != $pass_retry ) {
                            throw new Exception( 'Şifreler uyumlu değil' );
                        } else if ( !isset( $_POST[ "sozlesme" ] ) ) {
                            throw new Exception( 'Sözleşmeyi Kabul Etmediniz' );
                        } else if ( 1 == 2 ) {
                            throw new Exception( 'Güvenlik Kodunu Yanlış Girdiniz.' );
                        } else {
                            $kontrol = $odb->prepare( "SELECT login FROM account WHERE login = ?" );
                            $kontrol->execute( array(
                                 $username 
                            ) );
                            if ( $kontrol->rowCount() ) {
                                throw new Exception( "<b>" . $username . "</b> Adında bir üye zaten var" );
                            } else {
                                if ( ( $vt->a( "kayit_onay" ) == 2 && $vt->a( "kayit_hosgeldin" ) == 2 ) ) {
                                    $basari     = "Oyunumuza başarıyla kayıt oldunuz. E-mail adresinize gelen linke tıklayarak kaydınızı onaylayınız.";
                                    $email_onay = 1;
                                    $vt->token_ekle( 1, $username, $token );
									$mail_icerik = array('kayit', 1, $real_name, $username, $social_id, $phone_number, $vt->a( "link" ) . 'kayit_onay?token=' . $token . '&user=' . $username
									);
                                    $gonder      = $vt->mail_gonder( $eposta, "Oyunumuza Hoşgeldiniz Hesabınızı Onaylayın.", $mail_icerik );
                                    if ( !$gonder ) {
                                        form::hata( "Sistemdeki hatadan dolayı mail gönderemedik. Yöneticiler bu hata ile ilgileniyor.." );
                                    }
                                } else {
                                    if ( $vt->a( "kayit_onay" ) == 2 ) {
                                        $basari     = "Oyunumuza başarıyla kayıt oldunuz. E-mail adresinize gelen linke tıklayarak kaydınızı onaylayınız.";
                                        $email_onay = 1;
                                        $vt->token_ekle( 1, $username, $token );
										$mail_icerik = array('kayit', 2, $real_name, $vt->a( "link" ) . 'kayit_onay?token=' . $token . '&user=' . $username);

                                        $gonder      = $vt->mail_gonder( $eposta, "Hesabınızı Onaylayın", $mail_icerik );
                                        if ( !$gonder ) {
                                            form::hata( "Sistemdeki hatadan dolayı mail gönderemedik. Yöneticiler bu hata ile ilgileniyor.." );
                                        }
                                    } else if ( $vt->a( "kayit_hosgeldin" ) == 2 ) {
                                        $basari      = "Oyunumuza başarıyla kayıt oldunuz. Hesap Bilgileriniz E-mail adresinize gönderildi";
                                        $email_onay  = 0;
										$mail_icerik = array('kayit', 3, $real_name, $username, $social_id, $phone_number);
										$gonder      = $vt->mail_gonder( $eposta, "Oyunumuza Hoşgeldiniz", $mail_icerik );
                                        if ( !$gonder ) {
                                            form::hata( "Sistemdeki hatadan dolayı mail gönderemedik. Yöneticiler bu hata ile ilgileniyor.." );
                                        }
                                    } else if ( $vt->a( "kayit_hosgeldin" ) != 2 && $vt->a( "kayit_onay" ) != 2 ) {
                                        $basari     = "Oyunumuza başarıyla kayıt oldunuz.";
                                        $email_onay = 0;
                                    } else {
                                        $basari     = "";
                                        $email_onay = 0;
                                    }
                                }
                                $array_degistir = array(
                                     13 => $email_onay 
                                );
                                $veri_array     = array_replace( $veriler, $array_degistir );
                                $kayit          = $odb->prepare( "INSERT INTO account SET login = ?, real_name = ?, social_id = ?, email = ?, create_time = ?, gold_expire = ?, safebox_expire = ?, autoloot_expire = ?,
								fish_mind_expire = ?, marriage_fast_expire = ?, money_drop_rate_expire = ?, phone1 = ?, password = PASSWORD(?), email_onay = ?, question1 = ?, answer1 = ?, web_ip = ?,
								securitycode = ? $sutun1" );
                                $kayit->execute( $veri_array );
                                if ( $kayit->errorInfo()[2] == false ) {
                                    form::basari( $basari );
                                } else {
                                    form::hata( "Kayıt olunurken bir hata meydana geldi. Bu hata ile yöneticiler ilgileniyor." );
									$vt->hata_gonder($kayit->errorInfo()[2]);
                                }
                            }
                        }
                    }
                    catch ( Exception $e ) {
                        $error[ ] = $e->getMessage();
                    }
                    if ( $error ) {
                        foreach ( $error as $key => $value ) {
                            form::hata( $value );
                        }
                    }
                }
		
	}
	
}

?>