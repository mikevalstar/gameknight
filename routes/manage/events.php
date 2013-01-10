<?PHP

$events = new eventlist();
$events->rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 20;
$currentpage = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$filter = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : '';
if(isset($_REQUEST['orderby'])) $events->set_orderby($_REQUEST['orderby']);
if(isset($_REQUEST['direction'])) $events->set_direction($_REQUEST['direction']);
if(isset($_REQUEST['nameonly'])) $events->_filter_cols = array('event_name');

// display the page
if(getBestSupportedMimeType(array('text/html', 'application/json')) == 'application/json'){
    bTemplate::jsonResponse($events->resultsjson($filter, $currentpage));
}else{
    // fallback to html
    $T = new bTemplate('manage', 'events.tpl');
    $T->assign('results', $events->results($filter, $currentpage));
    $T->assign('currentpage', $currentpage);
    $T->assign('pagecount', $events->pagecount());
    $T->assign('rowcount', $events->rowcount());
    $T->assign('rows', $events->rows);
    $T->assign('filter', $filter);
    $T->assign('orderby', $events->orderby);
    $T->assign('direction', $events->direction);
    $T->run();
}
