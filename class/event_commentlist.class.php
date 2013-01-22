<?PHP

class event_commentlist extends baselist{

	var $_table 			= 'event_comment';
	var $_idcol 			= 'event_comment_pk';
	var $_filter_cols 		= array('event_comment_pk');
	var $_display_cols 		= array('author', 'comment', 'created_when');
	var $_del_col 			= 'deleted_when';

	/* settable properties / default values / Set as needed */
	var $rows 				= 100;
	var $orderby 			= 'created_when';
	var $direction			= 'asc';

	function __construct($for_event = false){
        if($for_event){
            $this->_ext_where[] = " AND `event_fk` = ".intval($for_event)." ";
        }
    }

}
