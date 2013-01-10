<?PHP

class userlist extends baselist{

    var $_table 			= 'user';
    var $_idcol 			= 'user_pk';
    var $_filter_cols 		= array('name_first', 'name_last', 'email', 'phone');
    var $_display_cols 		= array('user_pk', 'name_first', 'name_last', 'email', 'phone');
    var $_del_col 			= 'deleted_when';
    
    /* settable properties / default values / Set as needed */
    var $rows 				= 20;
    var $orderby 			= 'name_last';
    var $direction			= 'asc';

}