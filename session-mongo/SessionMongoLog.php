<?php

final class SessionMongoLog {
    
    static public $log_path;
    
    static public function set_log_dir($dir) {
        
        if(is_dir($dir) && is_writable($dir)) {
            self::$log_path = $dir;
        }else{
            echo 'Your log directory must be exists and writable.';
        }
        
    }
    
    static public function logging($content,$filename='session-mongo') {
        
        if( !is_null(self::$log_path) && !empty(self::$log_path) ) {
            
            $content_log = '['.date('d-M-Y H:i:s').'] '.$content."\n";
            $filepath = self::$log_path.strtolower($filename).'.log';
            $fp = fopen($filepath,'a+');
            
            if($fp) {
             
                flock($fp,LOCK_EX);
                fwrite($fp,$content_log);
                flock($fp,LOCK_UN);

                fclose($fp);
                @chmod($filepath,0755);
                
            }            
            
        }
        
    }
    
}