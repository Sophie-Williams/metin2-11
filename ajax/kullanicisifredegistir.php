<?php
class Post_kullanicisifredegistir
{

    public static function post()
    {

        global $vt, $ayar, $odb;

        $variable = gvn::get('variable');

        if ($variable == 'direk')
        {

            $pass_old = gvn::post('pass_old');
            $pass = gvn::post('pass');
            $pass_retry = gvn::post('pass_retry');
            $sifre_degis_token = gvn::post('sifre_degis_token');
            $captcha_code = gvn::post('captcha_code');
            $kontrol = $odb->prepare("SELECT COUNT(id) FROM account WHERE login = ? && password = PASSWORD(?)");
            $kontrol->execute(array(
                $_SESSION[$vt->a("isim") . "username"],
                $pass_old
            ));
            if (!$sifre_degis_token)
            {
                form::hata("Token yok");
            }
            else if ($ayar->sessionid != $sifre_degis_token)
            {
                form::hata("Token Hatası");
            }
            else if ($kontrol->fetchColumn() == 0)
            {
                form::hata("Eski şifrenizi yanlış girdiniz.");
            }
            else if (!$pass || $pass != $pass_retry)
            {
                form::hata("Şifreleriniz uyumlu değil");
            }
            else if ($_SESSION["captcha_code"] != $captcha_code)
            {
                form::hata("Güvenlik Kodunu Yanlış Girdiniz");
            }
            else
            {
                $guncelle = $odb->prepare("UPDATE account SET password = PASSWORD(?) WHERE login = ? && id = ?");
                $guncelle->execute(array(
                    $pass,
                    $_SESSION[$vt->a("isim") . "username"],
                    $_SESSION[$vt->a("isim") . "userid"]
                ));
                if ($guncelle->errorInfo() [2] == false)
                {
                    $vt->kullanici_log("Şifre değiştirildi");
                    form::basari("Şifrenizi Başarıyla Değiştirdiniz.");
                }
                else
                {
                    form::hata("Sistem hatası");
                }
            }

        }
        else if ($variable == 'mail')
        {

            $pass_mail = gvn::post('pass_mail');
            $pass_mail_retry = gvn::post('pass_mail_retry');
            $mail_captcha_code = gvn::post('mail_captcha_code');
            $sifre_degis_token = gvn::post('sifre_degis_token');
            if (!$sifre_degis_token)
            {
                $tema->hata("Token yok");
            }
            else if ($ayar->sessionid != $sifre_degis_token)
            {
                $tema->hata("Token Hatası");
            }
            else if (!$pass_mail || $pass_mail != $pass_mail_retry)
            {
                $tema->hata("Şifreleriniz uyumlu değil");
            }
            else if ($_SESSION["captcha_code"] != $mail_captcha_code)
            {
                $tema->hata("Güvenlik Kodunu Yanlış Girdiniz");
            }
            else
            {
                $guncelle = $odb->prepare("UPDATE account SET password = PASSWORD(?) WHERE login = ? && id = ?");
                $guncelle->execute(array(
                    $pass_mail,
                    $_SESSION[$vt->a("isim") . "username"],
                    $_SESSION[$vt->a("isim") . "userid"]
                ));
                if ($guncelle->errorInfo() [2] == false)
                {
                    $sifre_degis_tokeni_sil = $db->prepare("DELETE FROM token WHERE tur = ? && sid = ? && login = ?");
                    $sifre_degis_tokeni_sil->execute(array(
                        2,
                        server,
                        $_SESSION[$vt->a("isim") . "username"]
                    ));
                    $vt->kullanici_log("Şifre değiştirildi");
                    $tema->basari("Şifrenizi Başarıyla Değiştirdiniz.");
                }
                else
                {
                    $tema->hata("Sistem hatası");
                }
            }

        }

    }

}

?>
