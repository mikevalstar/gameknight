<?PHP

$games = new gameslist();
$games->rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 40;
$currentpage = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$filter = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : '';
if(isset($_REQUEST['orderby'])) $games->set_orderby($_REQUEST['orderby']);
if(isset($_REQUEST['direction'])) $games->set_direction($_REQUEST['direction']);
if(isset($_REQUEST['nameonly'])) $games->_filter_cols = array('game_name');

// display the page
if(getBestSupportedMimeType(array('text/html', 'application/json')) == 'application/json'){
    bTemplate::jsonResponse($games->resultsjson($filter, $currentpage));
}else{
    // fallback to html
    $T = new bTemplate('manage', 'games.tpl');
    $T->assign('results', $games->results($filter, $currentpage));
    $T->assign('currentpage', $currentpage);
    $T->assign('pagecount', $games->pagecount());
    $T->assign('rowcount', $games->rowcount());
    $T->assign('rows', $games->rows);
    $T->assign('filter', $filter);
    $T->assign('orderby', $games->orderby);
    $T->assign('direction', $games->direction);
    $T->run();
}
