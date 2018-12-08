<?php


session_start();

ob_start();

require_once(dirname(dirname(dirname(__FILE__))) . '/WM_settings/WM_database_ayar.php');

require_once(dirname(__FILE__) . '/class/db_connect.php');

$db = new db_connect();

require_once(dirname(__FILE__) . '/class/guvenlik.php');

require_once(dirname(__FILE__) . '/class/kontrol.php');

require_once(dirname(__FILE__) . '/class/getir.php');

require_once(dirname(__FILE__) . '/class/form.php');

require_once(dirname(__FILE__) . '/class/uye.php');

require_once(dirname(__FILE__) . '/class/pagination.php');


define('TEMA', '../WM_theme/WM_destek/'.getir::ayar('destek_tema').'/');

