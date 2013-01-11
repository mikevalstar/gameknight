<?PHP

$plays = new playlist();
$plays->rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 20;
$currentpage = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$filter = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : '';
if(isset($_REQUEST['orderby'])) $plays->set_orderby($_REQUEST['orderby']);
if(isset($_REQUEST['direction'])) $plays->set_direction($_REQUEST['direction']);

// display the page
if(getBestSupportedMimeType(array('text/html', 'application/json')) == 'application/json'){
    bTemplate::jsonResponse($plays->resultsjson($filter, $currentpage));
}else{
    // fallback to html
    $T = new bTemplate('manage', 'plays.tpl');
    $T->assign('results', $plays->results($filter, $currentpage));
    $T->assign('currentpage', $currentpage);
    $T->assign('pagecount', $plays->pagecount());
    $T->assign('rowcount', $plays->rowcount());
    $T->assign('rows', $plays->rows);
    $T->assign('filter', $filter);
    $T->assign('orderby', $plays->orderby);
    $T->assign('direction', $plays->direction);
    $T->run();
}
