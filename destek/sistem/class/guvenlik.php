<?php

class gvn{
	
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
	
	public static function website($site){
		if(filter_var($site, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) == true){
			return true;
		 }else{
			  return false;
		  }	
	  }
	  
	public static function ipadress($ip){
		if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) == true){
			return true;
		 }else{
			  return false;
		  }	
	  }
	
	public static function parola($passwd)
	{
		return preg_match("#.*^(?=.{5,20})(?=.*[a-z])(?=.*[0-9]).*$#", $passwd);
	}	
	
	public static function eposta($email)
	{
		return preg_match('/^[a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z\p{L}0-9]+(?:[.]?[_a-z\p{L}0-9-])*\.[a-z\p{L}0-9]+$/ui', $email);
	}
	
	public static function tarih($date)
	{
		return (bool)preg_match('/^([0-9]{4})-((0?[0-9])|(1[0-2]))-((0?[0-9])|([1-2][0-9])|(3[01]))( [0-9]{2}:[0-9]{2}:[0-9]{2})?$/', $date);
	}
	
	public static function zararliKodSil($pattern)
    {
        if (!defined('PREG_BAD_UTF8_OFFSET')) {
            return $pattern;
        }
        return preg_replace('/\\\[px]\{[a-z]{1,2}\}|(\/[a-z]*)u([a-z]*)$/i', '$1$2', $pattern);
    }	
	
	
}
