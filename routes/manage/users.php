<?PHP

$users = new userlist();
$users->rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 20;
$currentpage = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$filter = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : '';
if(isset($_REQUEST['orderby'])) $users->set_orderby($_REQUEST['orderby']);
if(isset($_REQUEST['direction'])) $users->set_direction($_REQUEST['direction']);
if(isset($_REQUEST['emailonly'])) $users->_filter_cols = array('email');

// display the page
if(getBestSupportedMimeType(array('text/html', 'application/json')) == 'application/json'){
    bTemplate::jsonResponse($users->resultsjson($filter, $currentpage));
}else{
    // fallback to html
    $T = new bTemplate('manage', 'users.tpl');
    $T->assign('results', $users->results($filter, $currentpage));
    $T->assign('currentpage', $currentpage);
    $T->assign('pagecount', $users->pagecount());
    $T->assign('rowcount', $users->rowcount());
    $T->assign('rows', $users->rows);
    $T->assign('filter', $filter);
    $T->assign('orderby', $users->orderby);
    $T->assign('direction', $users->direction);
    $T->run();
}
