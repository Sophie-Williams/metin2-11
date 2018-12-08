<?php
	
	class Post_sosyalduzenle
	{
		
		public static function post()
		{
			
			global $vt, $ayar, $odb, $get, $db;
			
			$variable = gvn::get('variable');
			
			if($variable == 'karakter'){
				
				$karakterID = $get['karakter_duzenle'];
				
				$karakterSeninmi = $odb->prepare("SELECT id FROM player.player WHERE account_id = ? && id = ?");
				$karakterSeninmi->execute(array($_SESSION[ $vt->a( "isim" ) . "userid" ], $karakterID));
				
				if($karakterSeninmi->rowCount()){
					
                            $facebook = gvn::post('facebook');
                            $youtube = gvn::post('youtube');
                            $instagram = gvn::post('instagram');
                            $sosyal_array = array(
                                 $facebook,
                                $youtube,
                                $instagram 
                            );
                            $imza         = nl2br( strip_tags( htmlspecialchars( gvn::post('imza') ), "<br />" ) );
                            $crsf_token   = gvn::post('crsf_token');
                            $captcha_code = gvn::post('captcha_code');
                            if ( !$crsf_token ) {
                                form::hata( "Token Yok" );
                            } else if ( $ayar->sessionid != $crsf_token ) {
                                form::hata( "Token Hatası" );
                            } else if ( $_SESSION[ "captcha_code" ] != $captcha_code ) {
                                form::hata( "Güvenlik Kodunu Yanlış Girdiniz" );
                            } else {
                                $guncelle   = $odb->prepare( "UPDATE player.player SET imza = ?, sosyal = ? WHERE id = ? && account_id = ?" );
                                $guncelle->execute( array(
                                     $imza,
                                    json_encode( $sosyal_array ),
                                    $karakterID,
                                    $_SESSION[ $vt->a( "isim" ) . "userid" ] 
                                ) );
                                if ( $guncelle->errorInfo()[2] == false ) {
                                    form::basari( "Sosyal ağ ayarlarınız başarıyla güncellendi" );
                                } else {
                                    form::hata( "Sistem hatası" );
                                }
                            }
					
				}
				else{
					form::hata('Bu karakterin sahibi olmadığınız için sosyal ayarlarını güncelleyemezsiniz');
				}
				
				
			}
			else if($variable == 'lonca'){
				
				if(isset($get['karakter']) && isset($get['guild_id'])){
					
					$loncaKontrol = $odb->prepare("SELECT id FROM player.guild WHERE id = ? && master = ?");
					$loncaKontrol->execute(array($get['guild_id'], $get['karakter']));
					
					if($loncaKontrol->rowCount()){
						
						$karakterSeninmi = $odb->prepare("SELECT id FROM player.player WHERE account_id = ? && id = ?");
						$karakterSeninmi->execute(array($_SESSION[ $vt->a( "isim" ) . "userid" ], $get['karakter']));
						
						if($karakterSeninmi->rowCount()){
                                $facebook = gvn::post('facebook');
                                $raidcall = gvn::post('raidcall');
                                $teamspeak3 = gvn::post('teamspeak3');
                                $sosyal_array = array(
                                     $facebook,
                                    $raidcall,
                                    $teamspeak3 
                                );
                                $crsf_token   = gvn::post('crsf_token');
                                $captcha_code = gvn::post('captcha_code');
                                if ( !$crsf_token ) {
                                    form::hata( "Token Yok" );
                                } else if ( $ayar->sessionid != $crsf_token ) {
                                    form::hata( "Token Hatası" );
                                } else if ( $_SESSION[ "captcha_code" ] != $captcha_code ) {
                                    form::hata( "Güvenlik Kodunu Yanlış Girdiniz" );
                                } else {
                                    $guncelle   = $odb->prepare( "UPDATE player.guild SET sosyal = ? WHERE id = ? && master = ?" );
                                    $guncelle->execute( array(
                                         json_encode( $sosyal_array ),
                                        $get['guild_id'],
                                        $get['karakter'] 
                                    ) );
                                    if ( $guncelle->errorInfo()[2] == false ) {
                                        form::basari( "Sosyal ağ ayarlarınız başarıyla güncellendi" );
                                    } else {
                                        form::hata( "Sistem hatası" );
                                    }
                                }
						}
						else{
							form::hata('Lonca başkanı sizin karakteriniz olmadığı için düzenleyemezsiniz');
						}
						
					}
					else{
						form::hata('Lonca başkanı sizin karakteriniz olmadığı için düzenleyemezsiniz');
					}
					
				}
				else{
					form::hata('Hatalı parametreler');
				}
				
			}
			
		}
		
	}
	
?>