<?PHP

class event_comment extends basedata{
	var $_table 		= 'event_comment';
	var $_keycol		= 'event_comment_pk';
	var $_cols 			= array('event_fk', 'author', 'comment');
	
	/* Messaging vars */
    var $_msg_obj_replace   = "Event Comment";

	var $_createbycol 	= 'created_by';
	var $_createwhencol = 'created_when';
	var $_delbycol 		= 'deleted_by';
	var $_delwhencol 	= 'deleted_when';
	var $_editbycol 	= 'modified_by';
	var $_editwhencol 	= 'modified_when';
    
    static function static_load($id){
        return new event_comment($id);
    }
    
}