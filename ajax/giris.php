<?php

class Post_giris{
	
	public static function post(){
		
		global $vt, $ayar, $odb;
		
         if ( $_POST ) {
            $username    = gvn::post('username');
            $password    = gvn::post('pass');
            $giris_token = gvn::post('giris_token');
            $error       = array( );
            try {
               if ( !$giris_token ) {
                  throw new Exception( 'Token bulunamadı' );
               } else if ( $ayar->sessionid != $giris_token ) {
                  throw new Exception( 'Token uyuşmuyor' );
               } else if ( !$username || !$password ) {
                  throw new Exception( 'Giriş yaparken boş alan bırakamazsınız' );
               } else if ( @$_COOKIE[ 'hata_cerez' ] == 5 ) {
                  throw new Exception( '5 kere üst üste yanlış girdiğinizden dolayı 15 dakka boyunca sisteme giriş yapamazsınız' );
               } else {
                  $kontrol = $odb->prepare( "SELECT login,password,id,status,email_onay,email FROM account WHERE login = ? && password = PASSWORD(?)" );
                  $kontrol->execute( array(
                      $username,
                     $password 
                  ) );
                  if ( $kontrol->rowCount() ) {
                     $afetch = $kontrol->fetch( PDO::FETCH_ASSOC );
                     if ( $afetch[ "status" ] == "block" || $afetch[ "status" ] == "BLOCK" ) {
                        throw new Exception( 'Hesabınız Banlandığından Dolayı giriş yapamıyorsunuz. İtiraz etmek, sebebini öğrenmek için lütfen destek bildirimi oluşturun' );
                     } else if ( $afetch[ "email_onay" ] == 1 && $vt->a( "kayit_onay" ) == 2 ) {
                        $_SESSION[ "hesap_onay" ]      = $username;
                        $_SESSION[ "hesap_onay_mail" ] = $afetch[ "email" ];
                        form::uyari( "Hesabınız Onaylanmamış. Onay Mailini Tekrar Göndermek İçin <a href='giris-yap?onay_gonder=1'> Tıklayınız </a>" );
                     } else {
                        $_SESSION[ "yeni_girdi" ]                  = true;
                        $_SESSION[ $vt->a( "isim" ) . "token" ]    = $ayar->sessionid;
                        $_SESSION[ $vt->a( "isim" ) . "username" ] = $username;
                        $_SESSION[ $vt->a( "isim" ) . "userid" ]   = $afetch[ "id" ];
						$_SESSION['destekUserıd'] = $afetch[ "id" ];
						$_SESSION['destekUsername'] = $username;
						$_SESSION['destek_giris'] = true;
						$_SESSION['destek_server'] = server;
						$_SESSION[ "market_user" ] = $username;
						$_SESSION[ "market_token" ] = true;
						$_SESSION[ "market_userid"]  = $afetch['id'];
						$_SESSION[ "market_server" ] = server;
                        form::basari( "Giriş yaptınız 2 saniye içinde yönlendirileceksiniz." );
                        printf( '<meta http-equiv="refresh" content="2;URL=' . $vt->url( 5 ) . '">' );
                     }
                  } else {
                     if ( isset( $_COOKIE[ 'hata_cerez' ] ) ) {
                        $yeni  = $_COOKIE[ 'hata_cerez' ] + 1;
                        $kalan = 5 - $yeni;
                        setcookie( "hata_cerez", $yeni, time() + 60 * 15 );
                        if ( $kalan == 0 ) {
                           $kalan_yazi = "5 kere üst üste yanlış girdiğiniz için sistem tarafından 15 dakka banlandınız";
                        } else {
                           $kalan_yazi = 'Tekrar denemek için ' . $kalan . ' şansınız var';
                        }
                     } else {
                        setcookie( "hata_cerez", 1, time() + 60 * 15 );
                        $yeni       = 1;
                        $kalan_yazi = "Tekrar denemek için 4 şansınız var";
                     }
                     throw new Exception( 'Kullanıcı adınızı veya şifrenizi yanlış girdiniz. ' . $kalan_yazi );
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