<?php
$konum = ".";

require_once 'WM_settings/WMayar.php';

@$ayar = new WMayar(".");

define('Sayfa_html', 'WM_Sayfalar/html_sayfa/' . gvn::get('islem') . '/');

$anaSayfa = json_decode($ayar->anasayfa);

if (count($anaSayfa) == 2 && isset($anaSayfa[1]))
{

    $serverVarmi = $db->prepare("SELECT id,link FROM server WHERE id = ?");
    $serverVarmi->execute(array(
        $anaSayfa[1]
    ));

    if ($serverVarmi->rowCount())
    {

        @define('server', $anaSayfa[1]);

        @$_SESSION["server_vt"] = server;

        @$server = $serverVarmi->fetch(PDO::FETCH_ASSOC);

        @$ayar = new WMayar(".", $anaSayfa[1]);

        @$vt = new WM_vt_settings($anaSayfa[1]);

    }
    else
    {
        printf('Server bulunamadı');
        exit(); // Server bulunamadı yazdır ve siteyi sonlandır.
        
    }

}

if ($anaSayfa[0] == 'index')
{ // ana sayfada index var.
    $index_tur = 1; // index türümüz 1 = direk ana sayfada
    if (empty($WMclass->ayar("index_tema")))
    { // index boşmu
        printf('İndex dizini boş olduğu için çekilemed');
    }
    else
    {
        if (is_dir($ayar->index_tema . $WMclass->ayar("index_tema")))
        { // böyle bir index teması var mı ?
            $cek = $ayar->index_tema . $ayar->index . "/index.php";
            require_once ($cek);
        }
        else
        {
            printf('İndex dizini bulunamadı');
        }
    }

}
else if ($anaSayfa[0] == 'yonlendir')
{ // ana sayfada direk yönlendirme var
    header('Location: ' . $server['link']); // Yönlendirme işlemi başlasın
    
}
else if ($anaSayfa[0] == 'direk')
{ // ana sayfayı direk gör
    $anaSayfatema = json_decode($WMclass->tema($anaSayfa[1]));

    if ($anaSayfatema[0] == 'tema')
    {

        $page = gvn::get('islem');

        define('WM_tema', 'WM_theme/WM_tema/' . $anaSayfatema[1] . '/');

        if (is_dir(WM_tema) && $anaSayfatema[1] != '')
        {
            if (empty($page))
            {
                $wmcp = new index;
            }
            else
            {
                $wmcp = new $page;
            }
            require_once WM_tema . 'index.php';
        }
        else
        {
            printf('<h1 align="center" style="margin-bottom:20px;">HATA !</h1><div style="border:1px solid #f00; padding:10px; margin:30px 0;"> dizin bulunamadı </div>');
        }

    }
    else
    {
        bakimCek($anaSayfatema[1]);
    }

}
else if ($anaSayfa[0] == 'index_tema')
{ // ana sayfada hem index hemde tema var
    $index_tur = 2;
    $vt = new WM_vt_settings(server);
    if (!isset($_SESSION["server_vt"]) || $_SESSION["server_vt"] != server)
    {
        $_SESSION["server_vt"] = server;
    }
    $ana_sayfa_tema = json_decode($WMclass->tema(server));
    if ($ana_sayfa_tema[0] == "tema")
    {
        if (!$WMclass->ayar("index_tema"))
        {
            echo "İndex Seçilmemiş";
        }
        else if (is_dir($ayar->index_tema . $WMclass->ayar("index_tema")))
        {
            define('WM_tema', 'WM_theme/WM_tema/' . $ana_sayfa_tema[1] . '/');
            $page = gvn::get('islem');
            if (($page == "" || !$page) && (strpos($_SERVER['REQUEST_URI'], "anasayfa") === false))
            {
                $cek = $ayar->index_tema . $ayar->index . "/index.php";
                require_once $cek;
            }
            else
            {
                @$ayar = new WMayar(".", server);
                if (is_dir(WM_tema) && $ana_sayfa_tema[1] != '')
                {
                    $vt = new WM_vt_settings(server);
                    if (!$page)
                    {
                        $wmcp = new index;
                    }
                    else
                    {
                        $wmcp = new $page;
                    }
                    require_once WM_tema . 'index.php';
                }
                else
                {
                    echo '<h1 align="center" style="margin-bottom:20px;">HATA !</h1><div style="border:1px solid #f00; padding:10px; margin:30px 0;"> dizin bulunamadı</div>';
                    exit;
                }
            }
        }
        else
        {
            echo '<h1 align="center" style="margin-bottom:20px;">HATA !</h1><div style="border:1px solid #f00; padding:10px; margin:30px 0;">İndex bulunamadı</div>';
            exit;
        }
    }
    else
    {
        define('WM_bakim', 'WM_theme/WM_bakim/' . $ana_sayfa_tema[1] . '/');
        if (is_dir(WM_bakim) && $ana_sayfa_tema[1] != '')
        {
            require WM_bakim . 'index.php';
        }
        else
        {
            echo '<h1 align="center" style="margin-bottom:20px;">HATA !</h1><div style="border:1px solid #f00; padding:10px; margin:30px 0;"> Bakım dizini bulunamadı </div>';
            exit;
        }
    }

}

function bakimCek($dizin)
{

    global $vt;

    define('WM_bakim', 'WM_theme/WM_bakim/' . $dizin . '/');
    if (is_dir(WM_bakim) && $dizin != '')
    {
        require_once WM_bakim . 'index.php';
    }
    else
    {
        echo '<h1 align="center" style="margin-bottom:20px;">HATA !</h1><div style="border:1px solid #f00; padding:10px; margin:30px 0;"> Bakım dizini bulunamadı </div>';
        exit;
    }

}

?>
