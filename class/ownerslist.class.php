<?PHP

class ownerslist extends baselist{

	var $_table 			= 'owner';
	var $_idcol 			= 'owner_pk';
	var $_filter_cols 		= array('user_pk');
	var $_display_cols 		= array('owner_pk', 'user_fk', 'game_fk');
	var $_del_col 			= 'deleted_when';

	/* settable properties / default values / Set as needed */
	var $rows 				= 20;
	var $orderby 			= 'owner_pk';
	var $direction			= 'asc';
	
	function __construct($for_game = false, $for_user = false){
	   if($for_game){
            $this->_table = array('user', 'owner');
            $this->_display_cols[] = 'name_first';
            $this->_display_cols[] = 'name_last';
            $this->orderby = 'name_first';
            $this->_ext_where[] = " AND `user_pk` = `user_fk` AND `game_fk` = ".intval($for_game)." ";
        }
        if($for_user){
            $this->_table = array('game', 'owner');
            $this->_display_cols[] = 'game_name';
            $this->_ext_where[] = " AND `game_pk` = `game_fk` AND `user_fk` = ".intval($for_user)." ";
        }
    }

}
