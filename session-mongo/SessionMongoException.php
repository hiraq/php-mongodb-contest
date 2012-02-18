<?php

final class SessionMongoException extends ErrorException {
    
    const SHOW_ERRORS = true;
    const DISABLE_ERRORS = false;
    
    static public function set_error($mode) {
        
        if($mode) {
            
            ini_set('display_errors','On');
            error_reporting(E_ALL);
            
        }else{
            
            ini_set('display_errors','Off');
            error_reporting(0);
            set_error_handler(array('SessionMongoException','set_error_exception'));
            
        }
        
    }
    
    public function set_error_exception($errno, $errstr, $errfile, $errline) {
        throw new SessionMongoException($errstr, 0, $errno, $errfile, $errline);
    }
    
    public function mongo_exception() {
        
        if(!is_null(SessionMongoLog::$log_path)) {
            
            $log_content = 'Error Exception : ';
            $log_content .= $this->getMessage();
            $log_content .= ', triggered at :'.$this->getFile();
            $log_content .= ', line :'.$this->getLine();

            //create exception log
            SessionMongoLog::logging($log_content,'session-mongo-exception');
            
        }        
        
    }
    
}