<?php

require "config.php";

class MSyncDB {
    var $db_conn;
}

require "functions.php";

require "logger.php";

class MediaSync {
	var $conf;
	var $func;
	var $db;
    var $logger;
	var $base_url = 'https://www.mediabank.org.uk/';
	
	function MediaSync($dbapp){
		$this->conf = new Config;
		$this->func = new Functions;
		$this->db = new MsyncDB;
        $this->logger = new Logger;
		
		$this->db->db_conn = mysql_pconnect($this->conf->mysql_config[$dbapp]['host'], $this->conf->mysql_config[$dbapp]['user'], $this->conf->mysql_config[$dbapp]['pass']) or die("Unable to open MySQL connection - " . mysql_error());
		mysql_select_db($this->conf->mysql_config[$dbapp]['name'], $this->db->db_conn) or die("Error Selecting DB");
	}
}

?>