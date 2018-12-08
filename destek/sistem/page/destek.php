<?php

class destek{
			
	
	
		public static function head(){
			
			return 'Destek talepleri';
			
		}
		
		public static function content(){
			
			global $db;
			
			$get = gvn::get('get');
			
			if(empty($get) || $get == 'yanitlanan' || $get == 'cevapbekleyen' || $get == 'kapanan' || $get == 'ara'){
			
			if(empty($get)){
				$destekListele = $db->prepare("SELECT * FROM destek WHERE acan = ? && sid = ?");
				$destekListele->execute(array($_SESSION['destekUsername'], $_SESSION['destek_server']));
			}
			else if($get == 'yanitlanan'){
				$destekListele = $db->prepare("SELECT * FROM destek WHERE acan = ? && sid = ? && durum = ?");
				$destekListele->execute(array($_SESSION['destekUsername'], $_SESSION['destek_server'], 1));
			}
			else if($get == 'cevapbekleyen'){
				$destekListele = $db->prepare("SELECT * FROM destek WHERE (acan = ? && sid = ?) && (durum = ? || durum = ?)");
				$destekListele->execute(array($_SESSION['destekUsername'], $_SESSION['destek_server'], 0, 2));
			}
			else if($get == 'kapanan'){
				$destekListele = $db->prepare("SELECT * FROM destek WHERE acan = ? && sid = ? && durum = ?");
				$destekListele->execute(array($_SESSION['destekUsername'], $_SESSION['destek_server'], 4));
			}
			else if($get == 'ara'){
				$arancak = gvn::post('search');
				$destekListele = $db->prepare("SELECT * FROM destek WHERE acan = ? && sid = ? && konu LIKE ?");
				$destekListele->execute(array($_SESSION['destekUsername'], $_SESSION['destek_server'], '%'.$arancak.'%'));
			}
			
						
			(kontrol::dosyaVarmi(TEMA.'destektalepleri.php')) ? require_once(TEMA.'destektalepleri.php') : print('dosya yok');
			
			}
			else{
				
				if($get == 'ac'){
					$destekKategorileri = $db->prepare("SELECT * FROM destek_kategori WHERE sid = ?");
					$destekKategorileri->execute(array($_SESSION['destek_server']));
					
					if(isset($_POST['konuac'])){
						
						$kategori = gvn::post('kategori');
						$konu = gvn::post('konu');
						$icerik = nl2br(gvn::post('icerik'), "</br>");
						
						if(empty($kategori) || empty($konu) || empty($icerik)){
							$gelenCevap = '<div class="alert alert-danger">Doldurulacak alanları boş bırakamazsınız</div>';
						}
						else{
							
							$kategoriKontrol = $db->prepare("SELECT id FROm destek_kategori WHERE id = ? && sid = ?");
							$kategoriKontrol->execute(array($kategori, $_SESSION['destek_server']));
							
							if($kategoriKontrol->rowCount()){
								$destekOlustur = $db->prepare("INSERT INTO destek SET acan = ?, sid = ?, kid = ?, konu = ?, icerik = ?, tarih = ?, yonlenen = ?, durum = ?");
								$destekOlustur->execute(array($_SESSION['destekUsername'], $_SESSION['destek_server'], $kategori, $konu, $icerik, date("Y-m-d H:i:s"), '[]', 0));
								if($destekOlustur->errorInfo()[2] == false){
									$gelenCevap = '<div class="alert alert-success">Talep başarıyla oluşturuldu. Yönlendiriliyorsunuz.</div>';
									printf('<meta http-equiv="refresh" content="2;URL=index.php?sayfa=destek&get=cevapbekleyen">');
								}
								else{
									$gelenCevap = '<div class="alert alert-danger">Sistem hatası meydana geldi</div>';
								}
							}
							else{
								$gelenCevap = '<div class="alert alert-danger">Kategori bulunamadığı için destek talebi oluşturamazsınız.</div>';
							}
							
						}
						
					}
					
					(kontrol::dosyaVarmi(TEMA.'talepac.php')) ? require_once(TEMA.'talepac.php') : print('dosya yok');
				}
				else{
					$destekKontrol = $db->prepare("SELECT destek.*, destek_kategori.isim as kategoriisim FROM destek LEFT JOIN destek_kategori ON destek_kategori.id = destek.kid WHERE destek.acan = ? && destek.sid = ? && destek.id = ?");
					$destekKontrol->execute(array($_SESSION['destekUsername'], $_SESSION['destek_server'], $get));
					
					if($destekKontrol->rowCount()){
					
						$destek = $destekKontrol->fetch(PDO::FETCH_ASSOC);
						
						$destekCevaplari = $db->prepare("SELECT * FROM destek_cevap WHERE tid = ? && sid = ?");
						$destekCevaplari->execute(array($destek['id'], $_SESSION['destek_server']));
						
						if(isset($_POST['cevapgonder'])){
							
							$destekGuncelHali = $db->prepare("SELECT durum FROM destek WHERE acan = ? && sid = ? && id = ?");
							$destekGuncelHali->execute(array($_SESSION['destekUsername'], $_SESSION['destek_server'], $get));
							
							if($destekGuncelHali->rowCount()){
								
								$guncelfetch = $destekGuncelHali->fetch(PDO::FETCH_ASSOC);
								
								if($guncelfetch['durum'] != 3 && $guncelfetch['durum'] != 4){
							
								$durumGuncelle = $db->prepare("UPDATE destek SET durum = ? WHERE sid = ? && acan = ? && id = ?");
								$durumGuncelle->execute(array(2, $_SESSION['destek_server'], $_SESSION['destekUsername'], $destek['id']));
								
								if($durumGuncelle->errorInfo()[2] == false){
									
									$cevabiGonder = $db->prepare("INSERT INTO destek_cevap SET tid = ?, sid = ?, ckisi = ?, cevap = ?, cevaplayan = ?, tarih = ?");
									$cevabiGonder->execute(array($destek['id'], $_SESSION['destek_server'], 1, nl2br(gvn::post('cevap'), "<br />"), $_SESSION['destekUsername'], date("Y-m-d H:i:s")));
									if($cevabiGonder->errorInfo()[2] == false){
										header('Location: #');
									}
									
								}
								
							  }
							
							}
							
						}
						
						(kontrol::dosyaVarmi(TEMA.'destekdetay.php')) ? require_once(TEMA.'destekdetay.php') : print('dosya yok');
					
					}
					else{
						$destekListele = $db->prepare("SELECT * FROM destek WHERE acan = ? && sid = ?");
						$destekListele->execute(array($_SESSION['destekUsername'], $_SESSION['destek_server']));
						(kontrol::dosyaVarmi(TEMA.'destektalepleri.php')) ? require_once(TEMA.'destektalepleri.php') : print('dosya yok');
					}
				}
			
			
			}
			
		}
	
}