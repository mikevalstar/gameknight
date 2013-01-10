<?PHP

class eventlist extends baselist{

	var $_table 			= 'event';
	var $_idcol 			= 'event_pk';
	var $_filter_cols 		= array('event_name', 'event_start');
	var $_display_cols 		= array('event_pk', 'event_name', 'event_start', 'event_end', 'participants');
	var $_del_col 			= 'deleted_when';

	/* settable properties / default values / Set as needed */
	var $rows 				= 20;
	var $orderby 			= 'event_start';
	var $direction			= 'asc';

}
