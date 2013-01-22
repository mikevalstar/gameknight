<?PHP

/*******************
 Permissions:
 
 999    = Superuser
 1      = Account user
 
 ********************/

class user extends basedata{
    var $_table             = 'user';
    var $_keycol            = 'user_pk';
    var $_cols              = array('email', 'phone', 'password', 'passkey', 'name_first', 'name_last', 'notify_email');
    
    /* Messaging vars */
    var $_msg_obj_replace   = "User";

    var $_createbycol       = 'created_by';
    var $_createwhencol     = 'created_when';
    var $_delbycol          = 'deleted_by';
    var $_delwhencol        = 'deleted_when';
    var $_editbycol         = 'modified_by';
    var $_editwhencol       = 'modified_when';
    
    var $_message_queue     = array();
    
    /*******************
     Login
     ********************/
     
    static function create($email, $password, $name_first, $name_last, $phone){
        if(user::user_exists($email)) return false;
        
        $par = array(
                'email' => $email,
                'password' => $password,
                'name_first' => $name_first,
                'name_last' => $name_last,
                'phone' => $phone,
                'notify_email' => 1
            );
        $user = new user();
        $user->save($par);
        
        return true;
    }
    
    static function login($email, $password, $min_permissions = 999){
        $sql = "SELECT `user_pk` FROM `user` WHERE `email` = ? AND ( `password` = MD5(?) OR `passkey` = MD5(?) )";
        $par = array($email, $password, $password);
        $results = DBQ::prepare_execute($sql, $par);
        if($row = $results->fetch()){

            $u = new user($row['user_pk']);
            if($u->highest_access() >= $min_permissions){
                $_SESSION['user'] = $u;
                if($_SESSION['user']->highest_access() == 999) $_SESSION['superuser'] = true;
                $_SESSION['user']->record_login();
                return true;
            }
        }
        
        return false;
    }
    
    static function logout(){
        $_SESSION['user'] = null;
        unset($_SESSION['user']);
        $_SESSION['superuser'] = null;
        unset($_SESSION['superuser']);
    }
    
    static function user_exists($email){
        $sql = "SELECT `user_pk` FROM `user` WHERE `email` = ? ";
        $par = array($email);
        $results = DBQ::prepare_execute($sql, $par);
        if($row = $results->fetch()){
            return $row['user_pk'];
        }
        return false;
    }
    
    static function lostpassword($email){
        if($uid = user::user_exists($email)){
            $user = new user($uid);
            $password = generatePassword(10, 8);
            $user->save(array('passkey' => md5($password)));
            email::send($_POST['email'], $user->name_first . ' ' . $user->name_last, "You Idiot - Password Retreival", "Your account on KG Game Night has been reset with the following password: {$password}");
            
            return true;
        }
        return false;
    }
    
    function record_login(){
        $sql = "INSERT INTO `user_login` (`user_fk`, `when`) VALUES (?, NOW())";
        $par = array($this->id);
        $results = DBQ::prepare_execute($sql, $par);
    }
    
    function highest_access(){
        return 999;
    }
    
    function user_loginlist(){
        $il = new user_loginlist($this->id);
        return $il;
    }
    
    /*******************
     Game Related
     ********************/
    function iown($game_pk){
        $sql = "SELECT count(*) cnt FROM `owner` WHERE user_fk = ? and game_fk = ?";
        $par = array($this->id, $game_pk);
        $results = DBQ::prepare_execute($sql, $par);
        if($row = $results->fetch()){
            if($row['cnt'] > 0){
                return true;
            }
        }
        return false;
    }
    
    function gameslist(){
        $gl = new gameslist($this->id);
        return $gl;
    }
    
    /*******************
     Saving / Deleting / Restoring
     ********************/
    function save($dd){
        if(isset($dd['password'])) $dd['password'] = md5($dd['password']);
    
        $ret = parent::save($dd);
        return $ret;
    }
    
    /*******************
     Message Queue (in session)
     Types: success, warning, error
     ********************/
     
    function msg($type, $message){
        $this->_message_queue[] = array('type' => $type, 'message' => $message);
        
        if(count($this->_message_queue) > 50){
            $this->_message_queue = array(array('type' => 'warning', 'message' => 'Message queue overflow error.'));
        }
    } 
    
    function messages(){
        if(count($this->_message_queue) > 0){
            $ret = $this->_message_queue;
            $this->_message_queue = array();
            return $ret;
        }else{
            return array();
        }
    }
}