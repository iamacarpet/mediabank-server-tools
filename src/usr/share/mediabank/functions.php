<?php

class Functions {
    var $done = true;
    
	function genAuthCode(){
		$salt_chars		=	array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'Z', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
		
		$code = "";
		for ($i = 0; $i < 25; $i++){
			$code .= $salt_chars[array_rand($salt_chars)];
		}
		
		return $code;
	}
	
	function saveFile($img,$fullpath){
		$ch = curl_init ($img);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		$rawdata=curl_exec($ch);
        $httpCode = curl_getinfo($ch , CURLINFO_HTTP_CODE);
		curl_close ($ch);
		if(file_exists($fullpath)){
			unlink($fullpath);
		}
		$fp = fopen($fullpath,'x');
		fwrite($fp, $rawdata);
		fclose($fp);
        if($httpCode != 200) {
            return false;
        } else {
            return true;
        }
	}
	
	function fetchData($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_ENCODING , "gzip");
    
        $response = curl_exec($ch);
      
        $httpCode = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch,CURLINFO_HEADER_SIZE);
        $data = substr( $response, $headerSize );
        curl_close($ch);
      
        if($httpCode != 200) {
            return false;
        }
      
        return $data;
	}
	
	function ping($host){
        exec(sprintf('ping -c 1 -W 5 %s', escapeshellarg($host)), $res, $rval);
        return $rval === 0;
	}
	
	function decodeSize($bytes){
    	$types = array( 'B', 'KB', 'MB', 'GB', 'TB' );
    	for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
    	return( round( $bytes, 2 ) . " " . $types[$i] );
	}
	
	function formatUptime($seconds) {
		$mins = intval($seconds / 60 % 60);
		$hours = intval($seconds / 3600 % 24);
		$days = intval($seconds / 86400);
  
		if ($days > 0) {
			$uptimeString .= $days;
			$uptimeString .= (($days == 1) ? " day" : " days");
		}
		if ($hours > 0) {
			$uptimeString .= (($days > 0) ? ", " : "") . $hours;
			$uptimeString .= (($hours == 1) ? " hour" : " hours");
		}
		if ($mins > 0) {
			$uptimeString .= (($days > 0 || $hours > 0) ? ", " : "") . $mins;
			$uptimeString .= (($mins == 1) ? " minute" : " minutes");
		}
		return $uptimeString;
	}
    
    function emailNotification($email, $subject, $title, $content){
        // Additional headers
        $headers = 'To: System Administrator <' . $email . '>' . "\r\n";
        $headers .= 'From: MediaBank Administration Service <sysadmin@mediabank.org.uk>' . "\r\n";
        $headers .= 'Subject: Mediabank Admin Notification: ' . $subject . "\r\n";
        
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
        $message = "<html><head><title>Mediabank Admin Notification</title><style type=\"text/css\" media=\"screen\">body { background: #e7e7e7; font-family: Verdana, sans-serif; font-size: 11pt; }#page { background: #ffffff; margin: 50px; border: 2px solid #c0c0c0; padding: 10px; }#header { background: #4b6983; border: 2px solid #7590ae; text-align: center; padding: 10px; color: #ffffff; }#header h1 { color: #ffffff; }#body { padding: 10px; }span.tt { font-family: monospace; }span.bold { font-weight: bold; }a:link { text-decoration: none; font-weight: bold; color: #C00; background: #ffc; }a:visited { text-decoration: none; font-weight: bold; color: #999; background: #ffc; }a:active { text-decoration: none; font-weight: bold; color: #F00; background: #FC0; }a:hover { text-decoration: none; color: #C00; background: #FC0; }</style></head>";
        $message .= "<body><div id=\"page\" align=\"center\"><div id=\"header\" align=\"center\"><h1>MediaBank Administration Service</h1>" . htmlentities($title) . "</div><div id=\"body\"><div align=\"center\">" . htmlentities($content) . "</div></div></div></body></html>";
        
        if (mail($email, $subject, $message, $headers)){
            return true;
        } else {
            return false;
        }
    }
}

?>