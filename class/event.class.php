<?PHP

class event extends basedata{
	var $_table 		= 'event';
	var $_keycol		= 'event_pk';
	var $_cols 			= array('event_name', 'event_start', 'event_end', 'event_text', 'participants', 'invite_sent', 'location');
	
	/* Messaging vars */
    var $_msg_obj_replace   = "Event";

	var $_createbycol 	= 'created_by';
	var $_createwhencol = 'created_when';
	var $_delbycol 		= 'deleted_by';
	var $_delwhencol 	= 'deleted_when';
	var $_editbycol 	= 'modified_by';
	var $_editwhencol 	= 'modified_when';
    
    static function static_load($id){
        return new event($id);
    }
    
    function coordinator(){
        return new user($this->created_by);
    }
    
    function is_coordinator($id){
        return $id == $this->created_by;
    }
    
    /* Games and voting */
    function games($user_pk){
        $sql = "SELECT * FROM (SELECT count(*) as votes, SUM(CASE WHEN user_fk = ? THEN 1 ELSE 0 END) has_voted, game_name, game_pk FROM `event_game_vote`, `game` WHERE `game_pk` = `game_fk` AND `event_fk` = ? GROUP BY `game_fk` ) as gamelist ORDER BY votes DESC";
        $par = array($user_pk, $this->id);
        $results = DBQ::prepare_execute($sql, $par);
        return $results->fetchAll();
    }
    
    function vote($user_pk, $game_pk){
        $sql = "INSERT INTO `event_game_vote` (`event_fk`,`user_fk`,`game_fk`) VALUES (?,?,?)";
        $par = array($this->id, $user_pk, $game_pk);
        $results = DBQ::prepare_execute($sql, $par);
        $this->_update_participant_count();
        
        if(isset($_SESSION['user'])) $_SESSION['user']->msg('success', 'Your vote has been noted. Your noticing of this notice has also been noted.');
    }
    
    function unvote($user_pk, $game_pk){
        $sql = "DELETE FROM `event_game_vote` WHERE `event_fk` = ?  AND `user_fk` = ? AND `game_fk` = ? ";
        $par = array($this->id, $user_pk, $game_pk);
        $results = DBQ::prepare_execute($sql, $par);
        $this->_update_participant_count();
        
        if(isset($_SESSION['user'])) $_SESSION['user']->msg('success', 'Your vote has been removed.');
    }
    
    /* Participants */
    
    function has_participated($id){
        return false;
    }
    
    function participant_response($id, $response){
        $sql = "INSERT INTO `event_participant` (`event_fk`,`user_fk`,`response`) VALUES (?,?,?) ON DUPLICATE KEY UPDATE `response` = ?";
        $par = array($this->id, $id, $response, $response);
        $results = DBQ::prepare_execute($sql, $par);
        $this->_update_participant_count();
        
        if(isset($_SESSION['user'])) $_SESSION['user']->msg('success', 'Your response has been noted. Your noticing of this notice has also been noted.');
    }
    
    function participants(){
        $sql = "SELECT * FROM `event_participant`, `user` WHERE `user_pk` = `user_fk` AND `event_fk` = ?";
        $par = array($this->id);
        $results = DBQ::prepare_execute($sql, $par);
        return $results->fetchAll();
    }
    
    function _update_participant_count(){
        $sql = "UPDATE `event` SET `participants` = (SELECT count(*) FROM `event_participant` WHERE event_fk = ? AND `response` = 'Yes') WHERE `event_pk` = ? ";
        $par = array($this->id, $this->id);
        $results = DBQ::prepare_execute($sql, $par);
    }
    
    /* invitations */
    
    function send_invite($type){
        // define text
        $coordinator = $this->coordinator();
        switch($type){
            case 'forceful':
                $subject = "KG Game Knight: You have been summoned: " . $this->event_name;
                $message = "{$coordinator->name_first} {$coordinator->name_last} has demanded your participation: \r\n" . $this->event_name . "\r\n"
                         . "{$this->event_start} {$this->event_end}  \r\n"
                         . "Record your response: http://gameknight.kg-dev.com/Events/{$this->id}/".prettyurlencode($this->event_name)."  \r\n"
                         . "ATTEND OR SUFFER THE CONSEQUENCES! \r\n"
                         . "---------------------" . "\r\n"
                         . "{$this->event_text}" . "\r\n"
                         . "---------------------" . "\r\n";
                break;
            default:
                $subject = "KG Game Knight: You are invited: " . $this->event_name;;
                $message = "{$coordinator->name_first} {$coordinator->name_last} has cordially invited you to: \r\n" . $this->event_name . "\r\n"
                         . "{$this->event_start} {$this->event_end}  \r\n"
                         . "Visit this link to record if you will be able to attend: http://gameknight.kg-dev.com/Events/{$this->id}/".prettyurlencode($this->event_name)."  \r\n"
                         . "---------------------" . "\r\n"
                         . "{$this->event_text}" . "\r\n"
                         . "---------------------" . "\r\n";
                break;
        }
        email::send_all($subject, $message);
        
        $sql = "UPDATE `event` SET `invite_sent` = 1 WHERE `event_pk` = ? ";
        $par = array($this->id);
        $results = DBQ::prepare_execute($sql, $par);
    }
    
    /* Comments */
    function comments(){
        return new event_commentlist($this->id);
    }
    
    function post_comment($user, $comment_text){
        if(trim($comment_text) == '') return;
        
        $comment = new event_comment();
        $comment->save(array('event_fk' => $this->id, 'author' => $user->name_first . ' ' . $user->name_last, 'comment' => $comment_text));
        
        $subject = "KG Game Knight: {$user->name_first} {$user->name_last} commented on: " . $this->event_name;
        $message = "{$user->name_first} {$user->name_last} wrote: \r\n\r\n {$comment_text}";
        foreach($this->participants() as $v){
            if($user->id != $v['user_pk'] && $v['response'] != 'No'){
                email::send($v['email'], $v['name_first'] . ' ' . $v['name_last'], $subject, $message);
            }
        }
    }
    
}
