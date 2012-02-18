<?php

require_once 'SessionMongoLog.php';
require_once 'SessionMongoException.php';
require_once 'Mongodb/Manager.php';
require_once 'Session/Manager.php';

final class SessionMongo {
    
    static public $MONGO_COMPAT = false;        
    
    private function __construct() {}
    
    static public function init() {
        
        if( !class_exists('Mongo') ) {            
            throw new SessionMongoException('PHP Mongo PECL not installed!');
        }else{
            self::$MONGO_COMPAT = true;
        }                
        
    }               
    
    static public function start() {
        
        if( self::$MONGO_COMPAT ) {                        

            $mongodb = Mongodb_Manager::get_instance();
            $mongodb->connect();
            $mongodb->set_db();    
            $mongodb->set_collection();
            
            $session_manager = Session_Manager::get_instance();  
            $session_manager->set_mongo($mongodb);
            
            session_set_save_handler(
                    array($session_manager,'open'),
                    array($session_manager,'close'),
                    array($session_manager,'read'),
                    array($session_manager,'write'),
                    array($session_manager,'destroy'),
                    array($session_manager,'gc'));
            
            session_start();
            
        }else{            
            throw new SessionMongoException('Please run SessionMongo::init first.');
        }
        
    }
    
}