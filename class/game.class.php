<?PHP

class game extends basedata{
	var $_table 		= 'game';
	var $_keycol		= 'game_pk';
	var $_cols 			= array('parent_fk', 'bgg_id', 'bgg_data', 'bgg_rating', 'game_name', 'min_players', 'max_players', 'setup_time', 'min_avg_len', 'max_avg_len', 'coop', 'team', 'preorder', 'notes', 'owners_txt', 'owners_count', 'game_weight', 'scoring_type');
	
	/* Messaging vars */
    var $_msg_obj_replace   = "Game";

	var $_createbycol 	= 'created_by';
	var $_createwhencol = 'created_when';
	var $_delbycol 		= 'deleted_by';
	var $_delwhencol 	= 'deleted_when';
	var $_editbycol 	= 'modified_by';
	var $_editwhencol 	= 'modified_when';
	
	static function lookup_by_name($name){
        $sql = "SELECT `venue_pk` FROM `game` WHERE `game_name` = ? AND `deleted_when` IS NULL";
        $par = array($name);
        $results = DBQ::prepare_execute($sql, $par);
        if($row = $results->fetch()){
            return new game($row['game_pk']);
        }
        
        return false;
    }
    
    static function static_load($id){
        return new game($id);
    }
    
    function save($dd = array(), $updatebgg = true){
        // owners quicktext
        $owners = $this->ownerslist()->results_all();
        $dd['owners_txt'] = '';
        foreach($owners as $v)
            $dd['owners_txt'] .= $v['name_first'] . ', ';
        $dd['owners_txt'] = trim($dd['owners_txt'], ', ');
        $dd['owners_count'] = count($owners);
        
        // parent game
        if(isset($dd['parent_fk']) && $dd['parent_fk'] == '') $dd['parent_fk'] = null;
    
        $ret = parent::save($dd);
        
        if($updatebgg)
            $this->bgg_refresh();
        
        return $ret;
    }
    
    // board game geek
    function bgg_refresh(){
        if($this->bgg_id > 0){
            $url = "http://www.boardgamegeek.com/xmlapi2/thing?stats=1&id=" . $this->bgg_id;
            $array = json_decode(json_encode((array)simplexml_load_string(file_get_contents($url))),1);
            if(isset($array['item'])){
                $array = $array['item'];
                
                $sql = "UPDATE `game` SET `bgg_data` = ? WHERE `game_pk` = ?";
                $par = array(serialize($array), $this->id);
                $results = DBQ::prepare_execute($sql, $par);
                
                $dd = array();
                if($this->notes == '') $dd['notes'] = htmlspecialchars_decode(str_replace("&#10;", "\n", $array['description']));
                if($this->min_players == 0) $dd['min_players'] = $array['minplayers']["@attributes"]['value'] ;
                if($this->max_players == 0) $dd['max_players'] = $array['maxplayers']["@attributes"]['value'] ;
                $dd['game_weight'] = $array['statistics']['ratings']['averageweight']['@attributes']['value'];
                $dd['bgg_rating'] = $array['statistics']['ratings']['average']["@attributes"]['value'];

                if(count($dd) > 0)
                    $this->save($dd, false);
            }
        }
    }
    
    function bgg(){
        if($this->bgg_data != ''){
            return unserialize($this->bgg_data);
        }
        return false;
    }
    
    // parent game
    function parent(){
        if($this->parent_fk != ''){
            $game = new game($this->parent_fk);
            return $game;
        }
        return false;
    }
    
    // Owners
    function ownerslist(){
        $l = new ownerslist($this->id);
        return $l;
    }
    
    function add_owner($id){
        $sql = "INSERT INTO `owner` (`game_fk`, `user_fk`) VALUES (?,?) ";
        $par = array($this->id, $id);
        $results = DBQ::prepare_execute($sql, $par);
        
        $this->save(array(), false);
    }
    
    function add_owner_by_email($email){
        $userpk = user::user_exists($email);
        if(!$userpk){
            if(isset($_SESSION['user'])) $_SESSION['user']->msg('error', 'User not found, could not add owner.');
            return false;
        }
        
        $this->add_owner($userpk);
    }
    
    function remove_owner($id, $user = false){
        if($user){
            $sql = "DELETE FROM `owner` WHERE `game_fk` = ? AND `user_fk` = ? ";
            $par = array($this->id, $user);
            $results = DBQ::prepare_execute($sql, $par);
        }else{
            $sql = "DELETE FROM `owner` WHERE `game_fk` = ? AND `owner_pk` = ? ";
            $par = array($this->id, $id);
            $results = DBQ::prepare_execute($sql, $par);
        }
        
        $this->save(array(), false);
    }
}
