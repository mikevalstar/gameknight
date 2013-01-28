<?PHP

$upcoming = new eventlist(true);
$newgames = new gameslist();
$newgames->rows = 10;
$newgames->orderby = "created_when";
$newgames->direction = 'desc';

// display the page

$T = new bTemplate('manage', 'index.tpl');
$T->assign('upcoming', $upcoming->results());

$T->assign('newgames', $newgames->results());

$T->run();
