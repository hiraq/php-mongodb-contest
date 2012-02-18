<?php

final class Mongodb_Manager {
    
    private $_mongo_instance = null;
    private $_mongo_collection_obj = null;
    private $_db = null;
        
    static private $_obj = null;
    static private $_mongo_options = null;
    static private $_mongo_db = null;
    static private $_mongo_collection = null;
    
    private function __construct() {}
    
    static public function get_instance() {
        
        if(is_null(self::$_obj)) {
            self::$_obj = new Mongodb_Manager();
        }
        
        return self::$_obj;
        
    }
    
    static public function set_mongo_options($options) {
        self::$_mongo_options = $options;
    }
    
    static public function set_mongo_db($dbname) {        
        self::$_mongo_db = $dbname;        
    }        
    
    static public function set_mongo_collection($colName) {        
        self::$_mongo_collection = $colName;        
    }
    
    public function connect($connect_prop='localhost:27017') {
        
        if(!is_array(self::$_mongo_options) && !is_null(self::$_mongo_options)) {
            $this->_mongo_instance = new Mongo($connect_prop,self::$_mongo_options);
        }else{
            $this->_mongo_instance = new Mongo($connect_prop);
        }
        
    }   
    
    public function set_db() {
        
        if(is_a($this->_mongo_instance,'Mongo') && !is_null(self::$_mongo_db)) {
            $this->_db = $this->_mongo_instance->selectDB(self::$_mongo_db);            
        }else{
            throw new SessionMongoException('Mongo db name nulled.');
        }
        
    }
    
    public function set_collection() {
        
        if($this->is_db_ready() && !is_null(self::$_mongo_collection)) {
            $this->_mongo_collection_obj = $this->_db->createCollection(self::$_mongo_collection);
        }else{
            throw new SessionMongoException('Mongo collection nulled.');
        }
        
    }
    
    public function get_collection() {
        
        if($this->is_db_ready() && !is_null($this->_mongo_collection_obj)) {
            return $this->_mongo_collection_obj;
        }else{
            throw new SessionMongoException('Mongo collection not ready for use.');
        }
       
    }
    
    public function get_dbs() {
        
        if(is_a($this->_mongo_instance,'Mongo')) {
            
            $dbs = $this->_mongo_instance->listDBs();
            if(!empty($dbs)) {
                return $dbs['databases'];
            }else{
                return false;
            }
            
        }
        
    }
    
    public function is_connected() {
        
        if(is_a($this->_mongo_instance,'Mongo')) {
            return $this->_mongo_instance->connected;
        }
        
    }
    
    public function is_db_ready() {
        return is_a($this->_db,'MongoDB');
    }
    
    public function get_mongo_db_instance() {
        return $this->_db;
    }
    
    public function get_mongo_instance() {
        return $this->_mongo_instance;
    }
    
}