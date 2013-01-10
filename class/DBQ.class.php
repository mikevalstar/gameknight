<?php

class DBQ{
    static function init(){
		$GLOBALS['DBQ'] = array();
		$GLOBALS['DBQ']['server'] 		= '';
		$GLOBALS['DBQ']['user'] 		= '';
		$GLOBALS['DBQ']['password'] 	= '';
		$GLOBALS['DBQ']['db'] 			= '';
		$GLOBALS['DBQ']['charset'] 	= 'utf8';
	}

	static function set($var, $val){
		switch($var){
			case 'server':
			case 'user':
			case 'password':
			case 'db':
			case 'charset':
				$GLOBALS['DBQ'][$var] = $val;
				break;
			default:
				trigger_error('Attempting to set ' . $var . ' is not possible.');
				break;
		}
	}

	static function prepare($query, $options = array(), $supress_error = false){
    	DBQ::_connect();
    	
		if ( $statement = $GLOBALS['DBQ']['con']->prepare( $query, $options ) ){
			return $statement;
		}elseif(!$supress_error){
			trigger_error($query . ' Produced an error: <b>' . print_r($GLOBALS['DBQ']['con']->errorInfo(), true) . '</b>');
			//trigger_error('Error Preparing Statement', $query, $options, print_r($GLOBALS['DBQ']['con']->errorInfo(), true));
			return false;
		}else{
			return false;
		}
	}

	static function execute($query, $aParams = array(), $supress_error = false) {
		DBQ::_connect();

		$results = $query->execute($aParams);
		if($results){
			return $results;
		}elseif(!$supress_error){//$query->debugDumpParams();
			trigger_error(' Produced an error: <b>' . print_r($GLOBALS['DBQ']['con']->errorInfo(), true) . '</b> query error: ' .  print_r($query->errorInfo(), true) . ' ' . $query->queryString);
			//trigger_error('Error Executing Query', $this->queryString, $aParams, print_r($this->errorInfo(), true));
			return false;
		}else{
			return false;
		}

		return $results;
	}

	static function prepare_execute($query, $parms = array(), $options = array(), $supress_error = false){
		$start = microtime(true);
		if(count($parms) == 0){
			$q =  DBQ::query($query, $supress_error);
			return $q;
		}elseif($q = DBQ::prepare($query, $options, $supress_error)){
			 DBQ::execute($q, $parms, $supress_error);
			 Tracker::tSQL($query . " -- " . print_r($parms, true), $start, microtime(true));
			return $q;
		}

		return false;
	}

	static function query($query, $supress_error = false){
		DBQ::_connect();

		$start = microtime(true);
		if ( $statement = $GLOBALS['DBQ']['con']->query( $query ) ){
			Tracker::tSQL($query, $start, microtime(true));
			return $statement;
		}elseif(!$supress_error){
			trigger_error($query . ' Produced an error: <b>' . print_r($GLOBALS['DBQ']['con']->errorInfo(), true) . '</b>');
			//trigger_error('Error Preparing Statement', $query, array(), print_r($this->errorInfo(), true));
			return false;
		}else{
			return false;
		}
	}

	static function numrows(){
		$sql 	= 'SELECT FOUND_ROWS() AS cnt';
		$query 	= DBQ::prepare_execute($sql);
		$row 	= $query->fetch();

		return $row['cnt'];
	}

	static function lastInsertId(){
		return $GLOBALS['DBQ']['con']->lastInsertId();
	}

	static function _connect(){
		if(!isset($GLOBALS['DBQ']['con'])){
			$start = microtime(true);
			$GLOBALS['DBQ']['con'] =
				new PDO("mysql:host={$GLOBALS['DBQ']['server']};dbname={$GLOBALS['DBQ']['db']}",
					$GLOBALS['DBQ']['user'],
					$GLOBALS['DBQ']['password'],
							array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
								PDO::ATTR_CASE => PDO::CASE_NATURAL,
								PDO::ATTR_STRINGIFY_FETCHES => true,
								PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC)
					);
			Tracker::tSQL('Database Connect', $start, microtime(true));
		}
	}
}