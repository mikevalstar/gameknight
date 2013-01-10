<?PHP

class baselist{
	/* private vars - do not set */
	var $_filter			= '';
	var $_rowcount 			= -1;

	/* base settings set these in your sub classes */
	var $_table 			= '';
	var $_idcol 			= false;
	var $_filter_cols 		= array();
	var $_display_cols 		= array();
	var $_del_col 			= '';
	var $_ext_where		    = array();
	var $_override_order 	= false; // set to override the orderby clause

	/* settable properties / default values / Set as needed */
	var $rows 				= 25;
	var $orderby 			= '';
	var $direction			= 'desc';

	function results($filter = '', $page = 1){
		$this->_filter = $filter;
		$sql = $this->_build_query($this->orderby, $this->direction, ($page - 1) * $this->rows, $this->rows);

		$query = DBQ::prepare_execute($sql[0], $sql[1]);

		$results = $query->fetchAll();

		$this->_rowcount = DBQ::numrows();

		return $results;
	}
	
	function results_all($filter = ''){
	    $oldrows = $this->rows;
		$this->rows = 10000;
		$results = $this->results($filter, 1);
		$this->rows = $oldrows;
		return $results;
	}
	
	function resultsjson($filter, $page){
    	$results = $this->results($filter, $page);
    	return array(
    	     'results' => $results
    	   , 'pagecount' => $this->pagecount()
    	   , 'rowcount' => intval($this->rowcount())
    	   , 'rows' => $this->rows
    	   , 'filter' => $filter
    	);
	}

	function rowcount(){
		return $this->_rowcount;
	}
	
	function pagecount(){
    	return ceil($this->rowcount() / $this->rows);
	}
	
	function set_orderby($col){
    	if(in_array($col, $this->_display_cols)) 
    	   $this->orderby = $col;
	}
	
	function set_direction($dir){
    	if(in_array(strtolower($dir), array("asc", "desc", ""))) 
    	   $this->direction = $dir;
	}

	function _build_query($column, $direction, $start, $count){
		$data = array();
		if($start < 0) $start = 0;

		// Search
		$search_middle = '';
		if($this->_filter != ''){
			if($this->_idcol && substr(trim($this->_filter), 0, 3) == 'id:'){
				$search_middle .= ' `'. $this->_idcol .'` = ? ';
				$data[] = substr(trim($this->_filter),3);
			}else{
			     foreach($this->_filter_cols as $v){
			         $filters = explode(" ", $this->_filter);
			         foreach($filters as $vv){
			             $search_middle .= ' OR `'.$v.'` LIKE ? ';
			             $data = array_merge($data, array('%'.$vv.'%'));
			         }
				}
			}
		}

		$search = ' true ';
		if($search_middle != '')
			$search = ' ('.trim($search_middle, ' OR ').') ';

		// Order By
		$order_by = $this->_override_order ? $this->_override_order : '`'.$column.'` '.$direction;


		// Delete exception
		$del_where = '';
		if($this->_del_col)
			$del_where = " AND `{$this->_del_col}` IS NULL ";
			
		// table
		if(is_array($this->_table)){
    		$table = '`'. implode('`,`', $this->_table) . '`';
		}else{
    		$table = '`'.$this->_table.'`';
		}

		// query
		$sql = 'SELECT SQL_CALC_FOUND_ROWS `'.implode($this->_display_cols, '`,`').'`
				FROM '.$table.'
				WHERE '. $search . $del_where . implode(" ", $this->_ext_where) . '
				ORDER BY '. $order_by .'
				LIMIT '.$start.','.$count;

		return array($sql, $data);
	}

	/*******************
     Testing Only
     ********************/
    function random_id(){ // warning can be very slow on large datasets
        if(is_array($this->_table)){
            $sql = "SELECT `{$this->_idcol}` FROM `{$this->_table[0]}` ORDER BY RAND() LIMIT 0,1;";
        }else{
            $sql = "SELECT `{$this->_idcol}` FROM `{$this->_table}` ORDER BY RAND() LIMIT 0,1;";
        }
        $query = DBQ::prepare_execute($sql);
		$results = $query->fetch();
		return $results[$this->_idcol];
    }

}