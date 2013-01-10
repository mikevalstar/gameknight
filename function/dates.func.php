<?PHP

function extract_date($mysqlstr){
    if($mysqlstr == '' || is_null($mysqlstr)) return date('Y-m-d');
    
    $dt = strtotime($mysqlstr);
    
    return date('Y-m-d', $dt);
}

function extract_time($mysqlstr){
    if($mysqlstr == '' || is_null($mysqlstr)) return date('h:i A'); 
    
    $dt = strtotime($mysqlstr);
    
    return date('h:i A', $dt);
}