<?PHP

class play extends basedata{
	var $_table 		= 'play';
	var $_keycol		= 'play_pk';
	var $_cols 			= array('game_fk', 'playtime', 'started', 'ranked');
	
	/* Messaging vars */
    var $_msg_obj_replace   = "Game Play";

	var $_createbycol 	= 'created_by';
	var $_createwhencol = 'created_when';
	var $_delbycol 		= 'deleted_by';
	var $_delwhencol 	= 'deleted_when';
	var $_editbycol 	= 'modified_by';
	var $_editwhencol 	= 'modified_when';
    
    static function static_load($id){
        return new play($id);
    }
    
    function save($dd = array()){
        $ret = parent::save($dd);
        
        $players = array();
        foreach($dd['user_fk'] as $k => $v){
           if($v != ''){
               $players[] = array(
                    'user_fk' => $v,
                    'score' => $dd['score'][$k],
                    'win' => $dd['win'][$k]
               );
           } 
        }
        
        $this->add_players($players);
        
        return $ret;
    }
    
    // parent game
    function game(){
        if($this->game_fk != ''){
            $game = new game($this->game_fk);
            return $game;
        }
        return false;
    }
    
    // Players
    function playerslist(){
        $sql = " SELECT * FROM `play_player`, `user` WHERE `user_fk` = `user_pk` AND `play_fk` = ? ";
        $par = array($this->id);
        $results = DBQ::prepare_execute($sql, $par);
        
        return $results->fetchAll();
    }
    
    function add_players($p = array()){
        foreach($p as $v){
            $sql = "INSERT INTO `play_player` (`play_fk`, `user_fk`, `score`, `win`, `rank_change`) VALUES (?,?,?,?,?) ";
            $par = array($this->id, $v['user_fk'], floatval($v['score']), $v['win'], 0);
            $results = DBQ::prepare_execute($sql, $par);
        }
    }
    
}
