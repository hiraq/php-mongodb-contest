<?php

final class Session_Manager {
    
    static private $_obj = null;
    private $_mongo = null;
    
    private function __construct() {}
    
    static public function get_instance() {
        
        if(is_null(self::$_obj)) {
            self::$_obj = new Session_Manager();
        }
        
        return self::$_obj;
        
    }
    
    public function set_mongo(Mongodb_Manager $mongodb) {
        $this->_mongo = $mongodb;        
    }
    
    public function open($save_path,$session_name) {
        return true;
    }
    
    public function close() {
        
        $collection = $this->_mongo->get_collection();
        $collection->remove(array('session_data' => ''));
        
        return true;
        
    }
    
    public function read($session_id) {
        
        $collection = $this->_mongo->get_collection();
        $data = $collection->find(array('session_id' => $session_id),array('session_data' => true));
        $iterator = iterator_to_array($data);   
        $iterator_keys = array_keys($iterator);
        $mongo_id = current($iterator_keys);
        $session_data = $iterator[$mongo_id]['session_data'];
        
        return (string) $session_data;
        
    }
    
    public function write($session_id,$session_data) {
        
        $collection = $this->_mongo->get_collection();
        
        $cursor = $collection->find(array(
            'session_id' => $session_id
        ));
        $iterator = iterator_to_array($cursor);
        
        if(!empty($iterator)) {
            
            $collection->update(array('session_id' => $session_id),array(
                '$set' => array(
                    'session_data' => $session_data,
                    'session_time' => time()
                )
            ));
            
        }else{
            
            $collection->ensureIndex(array('session_id' => 1 ),array('unique' => true,'dropDups' => true));
        
            $session_to_write = array(
                'session_id' => $session_id,
                'session_data' => $session_data,
                'session_time' => time()
            );

            $collection->insert($session_to_write);
            
        }
                
        return true;
        
    }
    
    public function destroy($session_id) {
        
        $collection = $this->_mongo->get_collection();
        $collection->remove(array('session_id' => $session_id));
        return true;
        
    }
    
    public function gc($maxlifetime) {
        echo $maxlifetime;
    }
    
}