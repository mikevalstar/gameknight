<?PHP

class playlist extends baselist{

	var $_table 			= array('play', 'game');
	var $_idcol 			= 'play_pk';
	var $_filter_cols 		= array('game_name', 'owners_txt');
	var $_display_cols 		= array('game_name', 'game_fk', 'play_pk', 'playtime', 'ranked', 'started');
	var $_del_col 			= 'play`.`deleted_when';

	/* settable properties / default values / Set as needed */
	var $rows 				= 20;
	var $orderby 			= 'started';
	var $direction			= 'desc';
	
	function __construct($for_user = false){
	   $this->_ext_where[] = " AND `game_pk` = `game_fk` ";
	   
        if($for_user){
            //$this->_ext_where[] = " AND `user_fk` = ".intval($for_user)." ";
        }
    }

}
