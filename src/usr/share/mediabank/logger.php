<?php

require "/usr/share/mediabank/colours.php";

class Logger {
    var $appname = 'Unknown App';
    var $filename = '/var/log/mediabank.log';
    var $fp;
    
    function init($appname, $filename){
        error_reporting(E_ERROR);
        
        ini_set('display_errors', 'Off');

        ini_set('error_log', $filename . '.php-errors');
        
        $this->appname = $appname;
        $this->filename = $filename;
        
        $this->_open();
    }
    
    function error($msg, $fatal = false, $display = false){        
        if (!$this->fp){
            $this->_open();
        }
        
        $clic = new Colors();
        $str = $clic->getColoredString(date('d/m/y h:i:s A') . ' - ', 'green', null) . $clic->getColoredString($this->appname . ': ', 'purple', null) . $clic->getColoredString($msg, 'red', null);
        
        if ($display){
            echo $clic->getColoredString(date('d/m/y h:i:s A') . ': ', 'green', null) . $clic->getColoredString($msg, 'purple', null) . "\n";
        }
        
        $this->_write($str);
        
        if ($fatal){
            exit();
        }
    }
    
    function _open(){
        $this->fp = fopen($this->filename, 'a');
        if (!$this->fp){
            die("Unable to open log file.");
        }
    }
    
    function _write($msg){
        while (! flock($this->fp, LOCK_EX) ){
    		usleep(200);	
		}
		
		fwrite($this->fp, $msg . "\n");
		
		flock($this->fp, LOCK_UN);
    }
    
    function _close(){
        fclose($this->fp);
    }
    
}

?>