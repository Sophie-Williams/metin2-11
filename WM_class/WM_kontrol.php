<?php
class gvn {
	
	public static function get($key, $default_value = false)
	{
		if (!isset($key) || empty($key) || !is_string($key))
			return false;
		$ret = (isset($_GET[$key]) ? $_GET[$key] : $default_value);

		if (is_string($ret) === true)
			$ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
		return !is_string($ret)? $ret : stripslashes($ret);
	}

	public static function post($key, $default_value = false)
	{
		if (!isset($key) || empty($key) || !is_string($key))
			return false;
		$ret = (isset($_POST[$key]) ? $_POST[$key] : $default_value);

		if (is_string($ret) === true)
			$ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
		return !is_string($ret)? $ret : stripslashes($ret);
	}
	
	public static function eposta($email)
	{
		return preg_match('/^[a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z\p{L}0-9]+(?:[.]?[_a-z\p{L}0-9-])*\.[a-z\p{L}0-9]+$/ui', $email);
	}

	
	public static function bread($s) {
			 $d = array('_');
			 $f = array(' ');
			 $s = str_replace($d,$f,$s);
			 return ucwords($s);
	}
	
	public static function eng($url)
	{
	 
	$url = trim($url);
	$find = array('<b>', '</b>');
	$url = str_replace ($find, '', $url);
	$url = preg_replace('/<(\/{0,1})img(.*?)(\/{0,1})\>/', 'image', $url);
	$find = array(' ', '&amp;amp;amp;quot;', '&amp;amp;amp;amp;', '&amp;amp;amp;', '\r\n', '\n', '/', '\\', '+', '<', '>');
	$url = str_replace ($find, '-', $url);
	$find = array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ë', 'Ê');
	$url = str_replace ($find, 'e', $url);
	$find = array('í', 'ý', 'ì', 'î', 'ï', 'I', 'Ý', 'Í', 'Ì', 'Î', 'Ï','İ','ı');
	$url = str_replace ($find, 'i', $url);
	$find = array('ó', 'ö', 'Ö', 'ò', 'ô', 'Ó', 'Ò', 'Ô');
	$url = str_replace ($find, 'o', $url);
	$find = array('á', 'ä', 'â', 'à', 'â', 'Ä', 'Â', 'Á', 'À', 'Â');
	$url = str_replace ($find, 'a', $url);
	$find = array('ú', 'ü', 'Ü', 'ù', 'û', 'Ú', 'Ù', 'Û');
	$url = str_replace ($find, 'u', $url);
	$find = array('ç', 'Ç');
	$url = str_replace ($find, 'c', $url);
	$find = array('þ', 'Þ','ş','Ş');
	$url = str_replace ($find, 's', $url);
	$find = array('ð', 'Ð','ğ','Ğ');
	$url = str_replace ($find, 'g', $url);
	$find = array('/[^A-Za-z0-9\-<>]/', '/[\-]+/', '/<&#91;^>]*>/');
	$repl = array('', '-', '');
	$url = preg_replace ($find, $repl, $url);
	$url = str_replace ('--', '-', $url);
	$url = strtolower($url);
	return $url;
	}
	
	
}
?>