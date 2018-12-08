<?php

class db_connect extends PDO {
	
    private $host = ahost;
	
    private $user = auser;
	
    private $password = apass;
	
    private $dbname = avt;
	
    private $port = 3306;
	
    private $charset = "utf8";
	
    public function __construct() {
		
		try{
		
        parent::__construct('mysql:host=' . $this->host . ';dbname=' . $this->dbname, $this->user, $this->password);
        $this->query('SET CHARACTER SET utf8');
        $this->query('SET NAMES utf8');
		
		}
		catch(PDOexception $e){
			print $e->getMessage();
		}
		
    }
	
}
?>