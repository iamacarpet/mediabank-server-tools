<?php

require "/usr/share/mediabank/colours.php";

class Logger {
    var $appname = 'Unknown App';
    var $filename = '/var/log/mediabank.log';
    var $fp;
    
    public function init($appname, $filename){
        error_reporting(E_ERROR);
        
        ini_set('display_errors', 'Off');

        ini_set('error_log', $filename . '.php-errors');
        
        $this->appname = $appname;
        $this->filename = $filename;
    }
    
    public function error($msg, $fatal = false, $display = false){        
        
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
    
    private function _open(){
        $this->fp = fopen($this->filename, 'a');
        if (!$this->fp){
            die("Unable to open log file.");
        }
    }
    
    private function _write($msg){
        $this->_open();
        
        while (! flock($this->fp, LOCK_EX) ){
    		usleep(200);	
		}
		
		fwrite($this->fp, $msg . "\n");
		
		flock($this->fp, LOCK_UN);
        
        $this->_close();
    }
    
    private function _close(){
        fclose($this->fp);
    }
    
}

?>