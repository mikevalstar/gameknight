<?PHP

class basedata{

    var $_table         = '';
    var $_keycol        = '';
    var $_cols             = array();    // do not include the modified / edited / del columns
                            //only columns you want to be able to display or edit

    var $_createbycol     = false;
    var $_createwhencol = false;
    var $_delbycol         = false;
    var $_delwhencol     = false;
    var $_editbycol     = false;
    var $_editwhencol     = false;
    
    /* Messaging vars */
    var $_msg_created_new   = "New <Object> created.";
    var $_msg_saved         = "<Object> saved.";
    var $_msg_deleted       = "<Object> has been deleted.";
    var $_msg_restored      = "<Object> has been restored.";
    var $_msg_notfound      = "Could not find the <Object> specified.";
    var $_msg_error_save    = "Could not save <Object>.";
    var $_msg_obj_replace   = "<Object>";

    /* private vars */
    var $_user_table     = 'user';
    var $_user_id_col    = 'user_pk';
    var $_id             = null;
    var $_isnew            = false;
    var $_data            = false;

    function __construct($id = null){
        if(is_null($id) || $id == 'new' || $id == ''){
            $this->_isnew = true;
        }else{
            $this->_id = $id;
        }
    }

    public function __get($var) {
        if(!$this->_data)
            $this->data();
        
        if($var == 'id') return $this->_data[$this->_keycol];

        if(!array_key_exists($var, $this->_data)){
            trigger_error('Attempted to retrieve non-existent property: ' . $var);
            return false;
        }

           return $this->_data[$var];
    }

    function isnew(){
        return $this->_isnew;
    }
    
    function isdeleted(){
        if(!$this->_delwhencol)
            return false;
        $data = $this->data();
        return !is_null($data[$this->_delwhencol]) || $data[$this->_delwhencol] != '';
    }

    function data($force = false){
        if($this->_data && !$force)
            return $this->_data; // cached data possibility

        if($this->_isnew && is_null($this->_id)){
            $ret = array();
            $ret[$this->_keycol] = 'New';
            foreach($this->_cols as $v)
                $ret[$v] = '';

            if($this->_createbycol)        $ret[$this->_createbycol] = '';
            if($this->_createwhencol)    $ret[$this->_createwhencol] = '';
            if($this->_delbycol)        $ret[$this->_delbycol] = '';
            if($this->_delwhencol)        $ret[$this->_delwhencol] = '';
            if($this->_editbycol)        $ret[$this->_editbycol] = '';
            if($this->_editwhencol)        $ret[$this->_editwhencol] = '';

            $this->_data = $ret;
            return $ret;
        }

        $this->_data = $this->_fetchdata();
        
        if(!$this->_data){
            $this->_msg('error', $this->_msg_notfound);
            return array();
        }
        
        return $this->_data;
    }

    function _fetchdata(){
        if($this->_createbycol){ // retrieve with the user that created the object
            $sql = "SELECT a.* ,
                            concat(c.`name_first`, ' ', c.`name_last`) as created_name
                    FROM `{$this->_table}` a 
                       LEFT JOIN `{$this->_user_table}` c ON c.`{$this->_user_id_col}` = a.`{$this->_createbycol}` 
                    WHERE a.`{$this->_keycol}` = ?";
        }else{
            $sql = "SELECT * FROM `{$this->_table}` WHERE `{$this->_keycol}` = ?";
        }
        $pars = array($this->_id);
        $query = DBQ::prepare_execute($sql, $pars);

        return $query->fetch();
    }

    /* auto save */
    function save($dd){

        if($this->_isnew && is_null($this->_id)){
            $sql = "INSERT INTO `{$this->_table}` SET ";
            $pars = array();
            foreach($this->_cols as $v){
                if(array_key_exists($v, $dd)){
                    $sql .= " `{$v}` = ?  ,";
                    $pars[] = $dd[$v];
                }
            }

            if($this->_createbycol){    $sql .= "`{$this->_createbycol}` = ? ,"; $pars[] = isset($_SESSION['user']) ? $_SESSION['user']->id : 0; }
            if($this->_createwhencol){    $sql .= "`{$this->_createwhencol}` = NOW() ,"; }
            
            if($this->_editbycol){        $sql .= "`{$this->_editbycol}` = ? ,"; $pars[] = isset($_SESSION['user']) ? $_SESSION['user']->id : 0; }
            if($this->_editwhencol){    $sql .= "`{$this->_editwhencol}` = NOW() ,"; }

            $sql = trim($sql, ',');

            if(!DBQ::prepare_execute($sql, $pars)){
                $this->_msg('error', $this->_msg_error_save);
                return false;
            }

            $this->_id = DBQ::lastInsertId();
            $this->_msg('success', $this->_msg_created_new);
            //$this->_isnew = false;
        }else{
            $sql = "UPDATE `{$this->_table}` SET ";
            $pars = array();
            foreach($this->_cols as $v){
                if(array_key_exists($v, $dd)){
                    $sql .= " `{$v}` = ?  ,";
                    $pars[] = $dd[$v];
                }
            }
            if($this->_editbycol){        $sql .= "`{$this->_editbycol}` = ? ,"; $pars[] = isset($_SESSION['user']) ? $_SESSION['user']->id : 0; }
            if($this->_editwhencol){    $sql .= "`{$this->_editwhencol}` = NOW() ,"; }
            
            $sql = trim($sql, ',') . " WHERE `{$this->_keycol}` = ?";
            $pars[] = $this->_id;

            if(!DBQ::prepare_execute($sql, $pars)){
                $this->_msg('error', $this->_msg_error_save);
                return false;
            }
            $this->_msg('success', $this->_msg_saved);
        }

        $this->data(true); // force data refresh
        return true;
    }

    function delete(){
        if($this->data()){
            $sql = "UPDATE `{$this->_table}` SET `{$this->_delwhencol}` = NOW(), `{$this->_delbycol}` = ? WHERE `{$this->_keycol}` = ?";
            $pars = array($_SESSION['user']->id, $this->_id);

            if(!DBQ::prepare_execute($sql, $pars)){
                $this->_msg('error', $this->_msg_error_save);
                return false;
            }

            $this->_msg('success', $this->_msg_deleted);
            return true;
        }else{
            return false;
        }
    }
    
    function restore(){
        if($this->data()){
            $sql = "UPDATE `{$this->_table}` SET `{$this->_delwhencol}` = null, `{$this->_delbycol}` = null WHERE `{$this->_keycol}` = ?";
            $pars = array($this->_id);

            if(!DBQ::prepare_execute($sql, $pars)){
                $this->_msg('error', $this->_msg_error_save);
                return false;
            }
            
            $this->_msg('success', $this->_msg_restored);

            return true;
        }else{
            return false;
        }
    }
    
    /**** messaging system ****/
    function _msg($type, $msg){
        if(isset($_SESSION['user'])) $_SESSION['user']->msg($type, str_replace('<Object>', $this->_msg_obj_replace, $msg));
    }
}