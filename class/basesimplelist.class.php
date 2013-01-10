<?PHP

class basesimplelist{

    // format:  array(array('key' => id, 'value' => ''));
    var $_table = false;
    var $_key = false;
    var $_val = false;
    var $_additional_val = false;
    var $_list = array();
    
    var $_msg_added     = "New <Object> added.";
    var $_msg_removed   = "<Object> removed.";
    
    function __construct(){
       if($this->_table){ // table exists so load from db
            $sql = "SELECT * FROM `{$this->_table}` ORDER BY {$this->_val}";
            $results = DBQ::prepare_execute($sql);
            $results = $results->fetchAll();
            if($results){
                foreach($results as $v){
                     $item = array('key' => $v[$this->_key], 'value' => $v[$this->_val]);
                     if($this->_additional_val)
                        foreach($this->_additional_val as $kk)  
                            $item[$kk] = $v[$kk];
                     $this->_list[] = $item;
                } 
            }
        }
    }

    function results(){
        return $this->_list;
    }
    
    function inlist($search){
        foreach($this->_list as $v){
            if(is_array($search)){
                foreach($search as $x)
                    if($v['value'] == trim($x))
                        return true;
                        
            }elseif($v['value'] == $search){
                return true;
            }
        }
        
        return false;
    }
    
    function display($search){
        foreach($this->_list as $v)
            if($v['key'] == $search)
                return $v['value'];
        
        return false;
    }
    
    function add($key, $value, $additional = false){
        if($additional){
            $par = array($key, $value);
            $cols = '';
            foreach($additional as $k => $v){
                $par[] = $k;
                $cols .= ",`{$k}`";
            }
            $sql = "INSERT INTO `{$this->_table}` (`{$this->_key}`,`$this->_val`".$cols.") VALUES (?,?".str_repeat(",?", count($additional)).")";
        }else if($this->_key != $this->_val){
            $sql = "INSERT INTO `{$this->_table}` (`{$this->_key}`,`$this->_val`) VALUES (?,?)";
            $par = array($key, $value);
        }else{
            $sql = "INSERT INTO `{$this->_table}` (`{$this->_val}`) VALUES (?)";
            $par = array($value);
        }
        $results = DBQ::prepare_execute($sql, $par);
        if($results != false) if(isset($_SESSION['user'])) $_SESSION['user']->msg('success', $this->_msg_added);
        return $results != false;
    }
    
    function delete($key, $value = false){
        if($value && $this->_key != $this->_val){
            $sql = "DELETE FROM `{$this->_table}` WHERE `{$this->_key}` = ? AND `$this->_val` = ?";
            $par = array($key, $value);
        }else{
            $sql = "DELETE FROM `{$this->_table}` WHERE `{$this->_key}` = ?";
            $par = array($key);
        }
        $results = DBQ::prepare_execute($sql, $par);
        if($results != false) if(isset($_SESSION['user'])) $_SESSION['user']->msg('success', $this->_msg_removed);
        return $results != false;
    }
    
    function random(){
        return $this->_list[array_rand($this->_list)];
    }
    
    function random_key(){
        return $this->_list[array_rand($this->_list)]['key'];
    }

}