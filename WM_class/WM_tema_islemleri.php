<?php
class WM_theme_settings {
    public function drop( $k ) {
        global $vt;
        $kes = explode( ",", $vt->a( "drop" ) );
        return $kes[ $k ];
    }
    public function kac_karakter( $id, $durum = false ) {
        global $odb;
        $online = $odb->prepare( "SELECT COUNT(player.name) as count FROM player.player WHERE account_id = ?" );
        $online->execute( array(
             $id 
        ) );
        $online = $online->fetchColumn();
        if ( $online == 0 ) {
            return 'Karakteri Yok';
        } else {
            if ( $durum != false ) {
                $yazi = " Karakteri var";
            } else {
                $yazi = "";
            }
            return $online . $yazi;
        }
    }
    public function _bayrak( $id ) {
        global $odb;
        $query = $odb->prepare( "SELECT empire FROM player_index WHERE id = ?" );
        $query->execute( array(
             $id 
        ) );
        $query = $query->fetch( PDO::FETCH_ASSOC );
        if ( $query[ "empire" ] == 1 ) {
            return 'Shinsoo Krallığı';
        } else if ( $query[ "empire" ] == 2 ) {
            return 'Chunjo Krallığı';
        } else if ( $query[ "empire" ] == 3 ) {
            return 'Jinno Krallığı';
        } else {
            return 'Belli Değil';
        }
    }
    public function sayfala( $link, $sayfa, $sayfa_goster, $toplam_sayfa ) {
        $sayfa_goster  = 5;
        $en_az_orta    = ceil( $sayfa_goster / 2 );
        $en_fazla_orta = ( $toplam_sayfa + 1 ) - $en_az_orta;
        $sayfa_orta    = $sayfa;
        if ( $sayfa_orta < $en_az_orta )
            $sayfa_orta = $en_az_orta;
        if ( $sayfa_orta > $en_fazla_orta )
            $sayfa_orta = $en_fazla_orta;
        $sol_sayfalar = round( $sayfa_orta - ( ( $sayfa_goster - 1 ) / 2 ) );
        $sag_sayfalar = round( ( ( $sayfa_goster - 1 ) / 2 ) + $sayfa_orta );
        if ( $sol_sayfalar < 1 )
            $sol_sayfalar = 1;
        if ( $sag_sayfalar > $toplam_sayfa )
            $sag_sayfalar = $toplam_sayfa;
        if ( $sayfa != 1 )
            echo ' <a class="sayfalama" href="' . $link . '1">İlk sayfa</a> ';
        if ( $sayfa != 1 )
            echo ' <a class="sayfalama" href="' . $link . '' . ( $sayfa - 1 ) . '">Önceki</a> ';
        for ( $s = $sol_sayfalar; $s <= $sag_sayfalar; $s++ ) {
            if ( $sayfa == $s ) {
                echo '<a class="sayfalama sayfalama_aktif" href="javascript:;">' . $s . '</a> ';
            } else {
                echo '<a class="sayfalama" href="' . $link . $s . '">' . $s . '</a> ';
            }
        }
        if ( $sayfa != $toplam_sayfa )
            echo ' <a class="sayfalama" href="' . $link . ( $sayfa + 1 ) . '">Sonraki</a> ';
        if ( $sayfa != $toplam_sayfa )
            echo ' <a class="sayfalama" href="' . $link . $toplam_sayfa . '">Son sayfa</a>';
    }
    public function hata( $deger ) {
?>

<div class="alert alert-danger" id="danger">
    <a class="close" href="javascript:;" onClick="document.getElementById('danger').setAttribute('style','display:none;');">×</a>
    <strong>Hata !</strong> <?= $deger; ?>
</div>

<?php
    }
    public function basari( $deger ) {
?>

<div class="alert alert-success" id="success">
    <a class="close" href="javascript:;" onClick="document.getElementById('success').setAttribute('style','display:none;');">×</a>
    <strong>Başarı </strong> <?= $deger; ?>
</div>

<?php
    }
    public function uyari( $deger ) {
?>

<div class="alert alert-warning" id="warning">
    <a class="close" href="javascript:;" onClick="document.getElementById('warning').setAttribute('style','display:none;');">×</a>
    <strong>Uyarı </strong> <?= $deger; ?>
</div>

<?php
    }
    public function skill_group( $job, $i ) {
        if ( $job == 0 || $job == 4 ) {
            if ( $i == 1 ) {
                $d = "Bedensel";
            } else if ( $i == 2 ) {
                $d = "Zihinsel";
            } else {
                $d = "Beceri Yok";
            }
        } else if ( $job == 1 || $job == 5 ) {
            if ( $i == 1 ) {
                $d = "Yakın Dövüş";
            } else if ( $i == 2 ) {
                $d = "Uzak Dövüş";
            } else {
                $d = "Beceri Yok";
            }
        } else if ( $job == 2 || $job == 6 ) {
            if ( $i == 1 ) {
                $d = "Kara Büyü";
            } else if ( $i == 2 ) {
                $d = "Büyülü Silah";
            } else {
                $d = "Beceri Yok";
            }
        } else if ( $job == 3 || $job == 7 ) {
            if ( $i == 1 ) {
                $d = "İyileştirme";
            } else if ( $i == 2 ) {
                $d = "Ejderha Gücü";
            } else {
                $d = "Beceri Yok";
            }
        } else if ( $job == 8 ) {
            if ( $i == 1 ) {
                $d = "Lycan";
            } else {
                $d = "Beceri Yok";
            }
        }
        return $d;
    }
    public function rutbe( $rutbe ) {
        if ( $rutbe > 11999 ) {
            return "<font color='#59ABE3'><b>Kahraman</b></font>";
        } elseif ( $rutbe > 7999 ) {
            return "<font color='#3498db'><b>Soylu</b></font>";
        } elseif ( $rutbe > 3999 ) {
            return "<font color='darkblue'><b>İyi</b></font>";
        } elseif ( $rutbe > 1999 ) {
            return "<font color='#2980b9'><b>Arkadaşça</b></font>";
        } elseif ( $rutbe > -3999 ) {
            return "Tarafsız";
        } elseif ( $rutbe > -7999 ) {
            return "<font color='#EB974E'>Agresif</font>";
        } elseif ( $rutbe > -11999 ) {
            return "<font color='#EB974E'><b>Hileli</b></font>";
        } elseif ( $rutbe < -20000 ) {
            return "<font color='#e74c3c'>Kötü Niyetli</font>";
        } elseif ( $rutbe >= -20000 ) {
            return "<font color='red'><b>Zalim</b></font>";
        }
    }
    public function zaman_cevir( $zaman, $tur = 1 ) {
        $zaman = strtotime( $zaman );
        if ( $tur == 1 ) {
            $zaman_farki = time() - $zaman;
            $ne          = "önce";
        } else {
            $zaman_farki = $zaman - time();
            $ne          = "sonra";
        }
        $saniye = $zaman_farki;
        $dakika = round( $zaman_farki / 60 );
        $saat   = round( $zaman_farki / 3600 );
        $gun    = round( $zaman_farki / 86400 );
        if ( $saniye < 60 ) {
            if ( $saniye == 0 ) {
                return "az " . $ne;
            } else {
                return $saniye . ' saniye ' . $ne;
            }
        } else if ( $dakika < 60 ) {
            return $dakika . ' dakika ' . $ne;
        } else if ( $saat < 24 ) {
            return $saat . ' saat ' . $ne;
        } else if ( $gun >= 1 ) {
            return $gun . ' gün ' . $ne;
        }
    }
    public function konum( $mapindex ) {
        if ( $mapindex == "1" ) {
            return "Yongan";
        } else if ( $mapindex == "3" ) {
            return "Jayang";
        } else if ( $mapindex == "4" ) {
            return "Jungrang";
        } else if ( $mapindex == "5" ) {
            return "Shinsoo - Hasun Dong";
        } else if ( $mapindex == "21" ) {
            return "Joan";
        } else if ( $mapindex == "23" ) {
            return "Bokjung";
        } else if ( $mapindex == "24" ) {
            return "Waryong";
        } else if ( $mapindex == "25" ) {
            return "Chunjo - Hasun Dong";
        } else if ( $mapindex == "41" ) {
            return "Pyungmo";
        } else if ( $mapindex == "43" ) {
            return "Bakra";
        } else if ( $mapindex == "44" ) {
            return "İmha";
        } else if ( $mapindex == "45" ) {
            return "Jinno - Hasun Dong";
        } else if ( $mapindex == "61" ) {
            return "Sohan Dağı";
        } else if ( $mapindex == "62" ) {
            return "Doyum Paper";
        } else if ( $mapindex == "63" ) {
            return "Yongbi Çölü";
        } else if ( $mapindex == "64" ) {
            return "Seuyong Vadisi";
        } else if ( $mapindex == "65" ) {
            return "Hwang Tapınağı";
        } else if ( $mapindex == "66" ) {
            return "Gumsan Kulesi";
        } else if ( $mapindex == "67" ) {
            return "Lungsam - Hayalet Orman";
        } else if ( $mapindex == "68" ) {
            return "Lungsam - Kızıl Orman";
        } else if ( $mapindex == "69" ) {
            return "Yılan Vadisi";
        } else if ( $mapindex == "70" ) {
            return "Devler Diyarı";
        } else if ( $mapindex == "71" ) {
            return "Kuahklo Dong";
        } else if ( $mapindex == "72" ) {
            return "Sürgün Mağarası";
        } else if ( $mapindex == "73" ) {
            return "Sürgün Mağarası";
        } else if ( $mapindex == "74" ) {
            return "Sohan Dağı";
        } else if ( $mapindex == "75" ) {
            return "Hwang Tapınağı";
        } else if ( $mapindex == "77" ) {
            return "Doyum Paper";
        } else if ( $mapindex == "78" ) {
            return "Seuyong Vadisi";
        } else if ( $mapindex == "79" ) {
            return "Sürgün Mağarası";
        } else if ( $mapindex == "81" ) {
            return "Nihah Salonu";
        } else if ( $mapindex == "100" ) {
            return "Alan";
        } else if ( $mapindex == "103" ) {
            return "T 01";
        } else if ( $mapindex == "104" ) {
            return "Örümcek Zindanı";
        } else if ( $mapindex == "105" ) {
            return "T 02";
        } else if ( $mapindex == "107" ) {
            return "Örümcek Zindanı";
        } else if ( $mapindex == "108" ) {
            return "Örümcek Zindanı";
        } else if ( $mapindex == "109" ) {
            return "Örümcek Zindanı";
        } else if ( $mapindex == "110" ) {
            return "T 03";
        } else if ( $mapindex == "111" ) {
            return "T 04";
        } else if ( $mapindex == "112" ) {
            return "Düello Haritası";
        } else if ( $mapindex == "113" ) {
            return "Ox Haritası";
        } else if ( $mapindex == "114" ) {
            return "Sungzi";
        } else if ( $mapindex == "118" ) {
            return "Sungzi";
        } else if ( $mapindex == "119" ) {
            return "Sungzi";
        } else if ( $mapindex == "120" ) {
            return "Sungzi";
        } else if ( $mapindex == "121" ) {
            return "Sungzi";
        } else if ( $mapindex == "122" ) {
            return "Sungzi";
        } else if ( $mapindex == "123" ) {
            return "Sungzi";
        } else if ( $mapindex == "124" ) {
            return "Sungzi";
        } else if ( $mapindex == "125" ) {
            return "Sungzi";
        } else if ( $mapindex == "126" ) {
            return "Sungzi";
        } else if ( $mapindex == "127" ) {
            return "Sungzi";
        } else if ( $mapindex == "128" ) {
            return "Sungzi";
        } else if ( $mapindex == "181" ) {
            return "3 Yol";
        } else if ( $mapindex == "182" ) {
            return "3 Yol";
        } else if ( $mapindex == "183" ) {
            return "3 Yol";
        } else if ( $mapindex == "184" ) {
            return "Sürgün Mağarası";
        } else if ( $mapindex == "185" ) {
            return "Sürgün Mağarası";
        } else if ( $mapindex == "186" ) {
            return "Sürgün Mağarası";
        } else if ( $mapindex == "187" ) {
            return "Sürgün Mağarası";
        } else if ( $mapindex == "188" ) {
            return "Sürgün Mağarası";
        } else if ( $mapindex == "189" ) {
            return "Sürgün Mağarası";
        } else if ( $mapindex == "206" ) {
            return "Devils Catacomb";
        } else if ( $mapindex == "207" ) {
            return "Nefrit Körfezi";
        } else if ( $mapindex == "208" ) {
            return "Ejderha Ateşi Burnu";
        } else if ( $mapindex == "209" ) {
            return "Guatama Uçurumu";
        } else if ( $mapindex == "210" ) {
            return "Yıldırım Dağları";
        } else if ( $mapindex == "211" ) {
            return "Örümcek Zindanı";
        } else {
            return "Belli Değil";
        }
    }
    public function istatistikler( $deger ) {
        global $vt;
        $tema_ayarlari = json_decode( $vt->a( "tema_a" ) );
        $istatistikler = json_decode( $tema_ayarlari[ 0 ] );
        return $istatistikler[ $deger ];
    }
    public function genel( $deger ) {
        global $vt;
        $tema_ayarlari = json_decode( $vt->a( "tema_a" ) );
        $genel         = json_decode( $tema_ayarlari[ 3 ] );
        return $genel[ $deger ];
    }
    public function droplar( $deger ) {
        global $vt;
        $tema_ayarlari = json_decode( $vt->a( "tema_a" ) );
        $drop          = json_decode( $tema_ayarlari[ 2 ] );
        return $drop[ $deger ];
    }
    public function footer( ) {
        global $vt;
        $duyuru = json_decode( $vt->a( "duyuru" ) );
        if ( $duyuru[ 0 ] == 1 ) {
?>

<div id="duyuru-alt"><span><b><?= html_entity_decode( $duyuru[ 1 ] ); ?> </span></b><br>
<?= html_entity_decode( $duyuru[ 2 ] ); ?>
<div>


<?php
        }
    }
    public function siralama( $deger ) {
        global $vt;
        $tema_ayarlari = json_decode( $vt->a( "tema_a" ) );
        $siralama      = json_decode( $tema_ayarlari[ 1 ] );
        return $siralama[ $deger ];
    }
    public function ayar_server( $deger ) {
        global $vt;
        $ayar = json_decode( $vt->a( "ayar" ) );
        return $ayar[ $deger ];
    }
    public function duyuru_icerik( $deger ) {
        global $vt;
        $duyuru_icerik = json_decode( $vt->a( "duyuru_2" ) );
        return $duyuru_icerik[ $deger ];
    }
    public function stiller( ) {
        if ( $this->ayar_server( 0 ) == 1 ) {
?>

<SCRIPT LANGUAGE="Javascript">
var isNS = (navigator.appName == "Netscape") ? 1 : 0;
var EnableRightClick = 0;
if(isNS)
document.captureEvents(Event.MOUSEDOWN||Event.MOUSEUP);
function mischandler(){
if(EnableRightClick==1){ return true; }
else {return false; }
}
function mousehandler(e){
if(EnableRightClick==1){ return true; }
var myevent = (isNS) ? e : event;
var eventbutton = (isNS) ? myevent.which : myevent.button;
if((eventbutton==2)||(eventbutton==3)) return false;
}
function keyhandler(e) {
var myevent = (isNS) ? e : window.event;
if (myevent.keyCode==96)
EnableRightClick = 1;
return;
}
document.oncontextmenu = mischandler;
document.onkeypress = keyhandler;
document.onmousedown = mousehandler;
document.onmouseup = mousehandler;
</script>

<?php
        }
    }
    public function jquery( $konum = "." ) {
        global $vt;
        if ( $konum == "." ) {
            $yer = "";
        } else {
            $yer = "../";
        }
?>

<link rel="stylesheet" type="text/css" href="<?= $yer; ?>WM_global/sweetalert.css" />
<script src="<?= $yer; ?>WM_global/eklenti.js"></script>
<script type="text/javascript" src="<?= $yer; ?>WM_global/tema.js"></script>
<script type="text/javascript" src="<?= $yer; ?>WM_global/app.js"></script>
<script type="text/javascript" src="<?= $yer; ?>WM_global/sweetalert.min.js"></script>
<script>yenile("<?= $konum; ?>");</script>

<?php
        if ( $this->ayar_server( 1 ) == 1 ) {
?>

<div class="koddostufacee kod-dostu-s-e" id="koddostu-face8">
<center>
<div id="koddostu_facebook_begen"><koddostu></koddostu>
<a href="javascript:void(0)" onclick="document.getElementById('koddostu-face8').style.display='none';" style="display:block;width:23px;height:23px;margin:0px;padding:0px;border:none;background-color:transparent;position:absolute;top:23px;right:70px;-webkit-border-radius: 12px;border-radius: 12px;"></a>
<iframe src="http://www.facebook.com/plugins/like.php?href=<?= $vt->sosyal( 0 ); ?>&send=false&layout=button_count&width=90&show_faces=false&action=like&colorscheme=light&font=segoe+ui&height=21&appId=515295435153698" scrolling="no" frameborder="0" style="position:absolute;bottom:82px;right:115px;border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe>
<script type="text/javascript">
document.write(unescape('%3c%73%74%79%6c%65%3e%23%6b%6f%64%64%6f%73%74%75%5f%66%61%63%65%62%6f%6f%6b%5f%62%65%67%65%6e%7b%70%6f%73%69%74%69%6f%6e%3a%72%65%6c%61%74%69%76%65%3b%77%69%64%74%68%3a%35%34%39%70%78%3b%68%65%69%67%68%74%3a%33%32%35%70%78%3b%62%61%63%6b%67%72%6f%75%6e%64%3a%74%72%61%6e%73%70%61%72%65%6e%74%20%75%72%6c%28%68%74%74%70%3a%2f%2f%32%2e%62%70%2e%62%6c%6f%67%73%70%6f%74%2e%63%6f%6d%2f%2d%32%35%44%74%66%39%70%6f%57%45%34%2f%55%43%75%6d%33%72%37%33%49%35%49%2f%41%41%41%41%41%41%41%41%4d%6f%6f%2f%6b%59%48%46%6b%2d%47%34%30%52%49%2f%73%31%36%30%30%2f%6b%6f%64%64%6f%73%74%75%2d%66%61%63%65%2e%70%6e%67%29%20%6e%6f%2d%72%65%70%65%61%74%20%74%6f%70%20%6c%65%66%74%3b%62%6f%72%64%65%72%3a%6e%6f%6e%65%3b%6d%61%72%67%69%6e%2d%74%6f%70%3a%31%30%25%3b%7d%0d%0a%64%69%76%2e%6b%6f%64%64%6f%73%74%75%66%61%63%65%65%7b%6c%69%6e%65%2d%68%65%69%67%68%74%3a%30%70%78%3b%70%6f%73%69%74%69%6f%6e%3a%66%69%78%65%64%3b%74%65%78%74%2d%61%6c%69%67%6e%3a%63%65%6e%74%65%72%3b%7a%2d%69%6e%64%65%78%3a%31%30%30%30%3b%7d%64%69%76%2e%6b%6f%64%2d%64%6f%73%74%75%2d%73%2d%65%7b%74%6f%70%3a%30%70%78%3b%6c%65%66%74%3a%30%70%78%3b%77%69%64%74%68%3a%31%30%30%25%3b%68%65%69%67%68%74%3a%31%30%30%25%3b%62%61%63%6b%67%72%6f%75%6e%64%3a%74%72%61%6e%73%70%61%72%65%6e%74%20%75%72%6c%28%68%74%74%70%3a%2f%2f%34%2e%62%70%2e%62%6c%6f%67%73%70%6f%74%2e%63%6f%6d%2f%2d%67%50%57%76%63%6f%67%78%46%79%67%2f%55%43%75%6f%6f%6c%68%6e%45%44%49%2f%41%41%41%41%41%41%41%41%4d%6f%77%2f%5a%38%31%51%4d%4d%68%4d%61%31%6b%2f%73%31%36%30%30%2f%62%6c%61%63%6b%2b%74%72%61%6e%73%70%61%72%65%6e%74%2e%70%6e%67%29%20%72%65%70%65%61%74%20%74%6f%70%20%6c%65%66%74%3b%7d%0d%0a%3c%2f%73%74%79%6c%65%3e%0d%0a%3c%73%74%79%6c%65%20%74%79%70%65%3d%22%74%65%78%74%2f%63%73%73%22%3e%64%69%76%2e%6b%6f%64%64%6f%73%74%75%66%61%63%65%65%7b%5f%70%6f%73%69%74%69%6f%6e%3a%61%62%73%6f%6c%75%74%65%3b%7d%64%69%76%2e%6b%6f%64%2d%64%6f%73%74%75%2d%73%2d%65%7b%5f%62%6f%74%74%6f%6d%3a%61%75%74%6f%3b%5f%74%6f%70%3a%65%78%70%72%65%73%73%69%6f%6e%28%69%65%36%3d%28%64%6f%63%75%6d%65%6e%74%2e%64%6f%63%75%6d%65%6e%74%45%6c%65%6d%65%6e%74%2e%73%63%72%6f%6c%6c%54%6f%70%2b%64%6f%63%75%6d%65%6e%74%2e%64%6f%63%75%6d%65%6e%74%45%6c%65%6d%65%6e%74%2e%63%6c%69%65%6e%74%48%65%69%67%68%74%20%2d%20%35%32%2b%22%70%78%22%29%20%29%3b%7d%3c%2f%73%74%79%6c%65%3e%0d%0a%3c%61%20%68%72%65%66%3d%22%68%74%74%70%3a%2f%2f%73%69%74%65%6e%65%65%6b%6c%65%6b%6f%64%6c%61%72%69%2e%62%6c%6f%67%73%70%6f%74%2e%63%6f%6d%2f%32%30%31%32%2f%30%38%2f%62%69%72%2d%6b%65%7a%2d%67%6f%72%75%6e%65%6e%2d%66%61%63%65%62%6f%6f%6b%2d%62%65%67%65%6e%2e%68%74%6d%6c%22%20%74%61%72%67%65%74%3d%22%5f%62%6c%61%6e%6b%22%20%73%74%79%6c%65%3d%22%70%6f%73%69%74%69%6f%6e%3a%61%62%73%6f%6c%75%74%65%3b%62%6f%74%74%6f%6d%3a%34%32%70%78%3b%72%69%67%68%74%3a%33%36%70%78%3b%63%6f%6c%6f%72%3a%23%42%43%43%46%46%35%20%21%69%6d%70%6f%72%74%61%6e%74%3b%66%6f%6e%74%2d%66%61%6d%69%6c%79%3a%54%61%68%6f%6d%61%2c%20%41%72%69%61%6c%2c%20%73%61%6e%73%2d%73%65%72%69%66%3b%66%6f%6e%74%2d%73%69%7a%65%3a%31%38%70%78%3b%74%65%78%74%2d%64%65%63%6f%72%61%74%69%6f%6e%3a%6e%6f%6e%65%20%21%69%6d%70%6f%72%74%61%6e%74%3b%22%3e%26%23%31%36%39%3b%3c%2f%61%3e'));
</script>
</div>
</center>
<script>
function readCookie(cookieName) {
 var re = new RegExp('[; ]'+cookieName+'=([^\s;]*)');
 var sMatch = (' '+document.cookie).match(re);
 if (cookieName && sMatch) return unescape(sMatch[1]);
 return '';
}</script>
<script>
var kdukig = readCookie('acildibibe2');
if (kdukig != '')
  {
document.getElementById('koddostu-face8').style.display='none';
  }
else {
 var isim = 'acildibibe2'; 
 var deger = 'kapali'; 
 var gunler = 2;   
 if (gunler) {
   var date = new Date();
   date.setTime(date.getTime()+(gunler*24*60*60*1000));
   var expires = "; expires="+date.toGMTString(); }
   else var expires = "";
   document.cookie = isim+"="+deger+expires+"; path=/";
}
</script>
</div>
<script src="http://www.koddostu.com/duzelt.js?no=236"></script>

<?php
        }
?>

<?php
        if ( $this->ayar_server( 3 ) == 1 ) {
?>

<!--Author: koddostu.com-->
<!--Licence: GNU GPL V2.0-->
<!--Name: Büyüleyici Duyuru Panosu Html Kodu!-->
<!--Koddostu Büyüleyici Duyuru Panosu Html Kodu START-->
<!--Bu çalışma Creative Commons Attribution-Gayriticari-NoDerivs 3.0 Unported Lisansı ile lisanslanmıştır.-->
<!--Telif sahibi:koddostu.com-->
<div id="menucon"><div id="koddostu-tex"><?= html_entity_decode( $this->duyuru_icerik( 1 ) ); ?>
</div></div>
<koddostu><style> #menucon{ position:fixed; bottom:10px; left:10px; width:130px;height:130px; background:transparent url(http://4.bp.blogspot.com/-d2P6c2gUTZs/UpPBn2rpnnI/AAAAAAAAi3w/fOfywJXwJ5g/s1600/see.png) no-repeat center center; transition: all 0.8s ease-in-out; background-size:cover; -webkit-transition: all 0.8s ease-in-out; transition-timing-function:ease-in-out; -webkit-transition-timing-function:ease-in-out; } #menucon:hover{ bottom:35px;left:35px; width:80px;height:80px; transform: rotate(720deg); -ms-transform: rotate(720deg); -webkit-transform: rotate(720deg); }#koddostu-tex{position:absolute;bottom:37px;left:38px; box-shadow: -1px 1px 5px 0px #888888; transition: all 0.8s ease-in-out; -webkit-transition: all 0.8s ease-in-out; transition-timing-function:ease-in-out; line-height:22px; -webkit-transition-timing-function:ease-in-out; opacity:0; width:0px;height:0px; padding:6px;color:#fff; font-family:Helvetica, Arial, sans-serif; font-weight:normal; text-decoration:none; font-size:14px; text-align:center; -webkit-border-radius: 8px; -webkit-border-bottom-left-radius: 0; border-radius: 8px; border-bottom-left-radius: 0; }</style><style> #menucon:hover #koddostu-tex{ width:200px;height:250px; background:#444; opacity:0.6;} </style></koddostu>
<!--Bu çalışma Creative Commons Attribution-Gayriticari-NoDerivs 3.0 Unported Lisansı ile lisanslanmıştır.-->
<!--Telif sahibi:koddostu.com-->
<!--Koddostu Büyüleyici Duyuru Panosu Html Kodu STOP-->
<script src="http://www.koddostu.com/duzelt.js?no=118"></script>

	
<?php
        }
    }
}
?>