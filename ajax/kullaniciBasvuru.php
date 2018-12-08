<?php
class Post_kullaniciBasvuru
{

    public static function post()
    {

        global $vt, $ayar, $odb, $get, $db;

        $basvuruID = $get['detay_basvuru'];

        $variable = gvn::get('variable');

        $basvuruVarmi = $db->prepare("SELECT id,bitis,bitis_tur,konu,basvuranlar,onaylananlar,red_edilenler,lonca_sart FROM basvurular WHERE id = ? && sid = ?");
        $basvuruVarmi->execute(array(
            $basvuruID,
            server
        ));

        if ($basvuruVarmi->rowCount())
        {

            $basvuru = $basvuruVarmi->fetch(PDO::FETCH_ASSOC);

            $array_basvurular = json_decode($basvuru["basvuranlar"], true);
            $array_onaylanan = json_decode($basvuru["onaylananlar"], true);
            $array_red = json_decode($basvuru["red_edilenler"], true);

            if ($variable == 'normal')
            {

                $icerik = gvn::post('icerik');
                $basvuru_token = gvn::post('basvuru_token');
                $captcha_code = gvn::post('captcha_code');
                if (!$basvuru_token)
                {
                    form::hata("Token bulunamadı");
                }
                else if ($basvuru_token != $ayar->sessionid)
                {
                    form::hata("Token hatası");
                }
                else if ($captcha_code != $_SESSION["captcha_code"])
                {
                    form::hata("Güvenlik kodunu yanlış girdiniz");
                }
                else if (!$icerik)
                {
                    form::hata("Başvuru içeriği boş bırakılamaz.");
                }
                else if (($vt->zaman_bittimi($basvuru["bitis"])) && ($basvuru["bitis_tur"] == 1))
                {
                    form::hata("Başvuru zamanı dolmuştur. Daha başvuru alamayız");
                }
                else
                {
                    if (isset($array_basvurular[$_SESSION[$vt->a("isim") . "username"]]))
                    {
                        form::hata("Daha önceden başvuru zaten yapmışsınız");
                    }
                    else if (isset($array_onaylanan[$_SESSION[$vt->a("isim") . "username"]]))
                    {
                        form::hata("Başvurunuz zaten onaylanmış. Daha başvuru yapamazsınız.");
                    }
                    else if (isset($array_red[$_SESSION[$vt->a("isim") . "username"]]))
                    {
                        form::hata("Başvurunuz red edilmiş. Daha başvuru yapamazsınız.");
                    }
                    else
                    {
                        $array_ekle = array(
                            $_SESSION[$vt->a("isim") . "username"] => $icerik
                        );
                        $yeni_array = array_replace($array_ekle, $array_basvurular);
                        $guncelle = $db->prepare("UPDATE basvurular SET basvuranlar = ? WHERE id = ? && sid = ?");
                        $guncelle->execute(array(
                            json_encode($yeni_array) ,
                            $basvuru["id"],
                            server
                        ));
                        if ($guncelle->errorInfo() [2] == false)
                        {
                            $vt->bildirim_gonder("admin", 3, $basvuru["konu"] . " adlı başvuru formuna 1 yeni başvuru var", $basvuru["id"], 2);
                            form::basari("Başvurunuzu başarıyla yaptınız.");
                        }
                        else
                        {
                            form::hata("Sistem hatası");
                        }
                    }
                }
            }
            else if ($variable == 'lonca')
            {

                $lonca_sart = json_decode($basvuru["lonca_sart"]);
                $lonca_isim = gvn::post('lonca_isim'); 
				$basvuru_token = gvn::post('basvuru_token');
                $captcha_code = gvn::post('captcha_code');
                $kontrol1 = $odb->prepare("SELECT id FROM player.guild WHERE name = ?");
                $kontrol1->execute(array(
                    $lonca_isim
                ));
                $kontrol1 = $kontrol1->rowCount();
                $kontrol = $odb->prepare("SELECT guild.name, guild.id AS lonca_id, guild.level, account.id FROM player.guild LEFT JOIN player.player ON guild.master = player.id
							LEFT JOIN account.account ON player.account_id = account.id WHERE guild.name = ? GROUP BY player.account_id
							");
                $kontrol->execute(array(
                    $lonca_isim
                ));
                $kontrol_fetch = $kontrol->fetch(PDO::FETCH_ASSOC);
                $uyeler = $odb->prepare("SELECT pid FROM player.guild_member WHERE guild_id = ?");
                $uyeler->execute(array(
                    $kontrol_fetch["lonca_id"]
                ));
                $uyeler = $uyeler->rowCount();
                if (!$basvuru_token)
                {
                    form::hata("Token bulunamadı");
                }
                else if ($basvuru_token != $ayar->sessionid)
                {
                    form::hata("Token hatası");
                }
                else if ($captcha_code != $_SESSION["captcha_code"])
                {
                    form::hata("Güvenlik kodunu yanlış girdiniz");
                }
                else if (!$lonca_isim)
                {
                    form::hata("Lonca ismi boş bırakılamaz");
                }
                else if ($kontrol1 == 0)
                {
                    form::hata("Böyle bir lonca bulunamadı");
                }
                else if ($kontrol_fetch["id"] != $_SESSION[$vt->a("isim") . "userid"])
                {
                    form::hata("Bu loncanın lideri siz değilsiniz");
                }
                else if ($kontrol_fetch["level"] < $lonca_sart[1])
                {
                    form::hata("Loncanızın leveli : <b> " . $kontrol_fetch["level"] . " </b> Başvuru şartı ise : <b>" . $lonca_sart[1] . " level</b> dir. Bu yüzden başvurunuzu alamıyoruz");
                }
                else if ($uyeler < $lonca_sart[0])
                {
                    form::hata("Loncanızın üye sayısı : <b> " . $uyeler . " </b> Başvuru şartı ise : <b>" . $lonca_sart[0] . " kişi</b> dir. Bu yüzden başvurunuzu alamıyoruz");
                }
                else if (($vt->zaman_bittimi($basvuru["bitis"])) && ($basvuru["bitis_tur"] == 1))
                {
                    form::hata("Başvuru zamanı dolmuştur. Daha başvuru alamayız");
                }
                else
                {
                    if (isset($array_basvurular[$_SESSION[$vt->a("isim") . "username"]]))
                    {
                        form::hata("Daha önceden başvuru zaten yapmışsınız");
                    }
                    else if (isset($array_onaylanan[$_SESSION[$vt->a("isim") . "username"]]))
                    {
                        form::hata("Başvurunuz zaten onaylanmış. Daha başvuru yapamazsınız.");
                    }
                    else if (isset($array_red[$_SESSION[$vt->a("isim") . "username"]]))
                    {
                        form::hata("Başvurunuz red edilmiş. Daha başvuru yapamazsınız.");
                    }
                    else
                    {
                        $array_ekle = array(
                            $_SESSION[$vt->a("isim") . "username"] => $lonca_isim
                        );
                        $yeni_array = array_replace($array_ekle, $array_basvurular);
                        $guncelle = $db->prepare("UPDATE basvurular SET basvuranlar = ? WHERE id = ? && sid = ?");
                        $guncelle->execute(array(
                            json_encode($yeni_array) ,
                            $basvuru["id"],
                            server
                        ));
                        if ($guncelle->errorInfo() [2] == false)
                        {
                            $vt->bildirim_gonder("admin", 3, $basvuru["konu"] . " adlı lonca başvuru formuna 1 yeni başvuru var", $basvuru["id"], 2);
                            form::basari("Başvurunuzu başarıyla yaptınız.");
                        }
                        else
                        {
                            form::hata("Sistem hatası");
                        }
                    }
                }

            }

        }
        else
        {
            form::hata('Başvuru formu bulunamadı.');
        }

    }

}

?>
