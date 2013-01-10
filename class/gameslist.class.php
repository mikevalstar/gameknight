<?PHP

class gameslist extends baselist{

	var $_table 			= 'game';
	var $_idcol 			= 'game_pk';
	var $_filter_cols 		= array('game_name', 'owners_txt');
	var $_display_cols 		= array('game_pk', 'parent_fk', 'game_name', 'min_players', 'max_players', 'setup_time', 'min_avg_len', 'max_avg_len', 'coop', 'team', 'owners_txt', 'preorder', 'game_weight', 'bgg_rating');
	var $_del_col 			= 'deleted_when';

	/* settable properties / default values / Set as needed */
	var $rows 				= 20;
	var $orderby 			= 'game_name';
	var $direction			= 'asc';
	
	function __construct($for_user = false){
        if($for_user){
            $this->_table = array('game', 'owner');
            $this->_ext_where[] = " AND `game_pk` = `game_fk` AND `owner`.`user_fk` = ".intval($for_user)." ";
        }
    }

}
