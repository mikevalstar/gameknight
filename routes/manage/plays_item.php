<?PHP

$play = new play($PATH[1]);

if(isset($_POST['action'])){
    switch($_POST['action']){
        case 'save':
            $_POST['started'] = date('Y-m-d H:i:s', strtotime($_POST['started'] . ' ' . $_POST['started_time']));
            $play->save($_POST);
            header('location: /plays/' . $play->id );
            die();
            break;
        case 'delete':
            $play->delete();
            header('location: /plays');
            die();
            break;
        case 'restore':
            $play->restore();
            header('location: /plays/' . $play->id );
            die();
            break;
    }
}

// display the page
if(getBestSupportedMimeType(array('text/html', 'application/json')) == 'application/json'){
    bTemplate::jsonResponse($play->resultsjson($filter, $currentpage));
}else{
    // fallback to html
    if(!$play->isnew()){
        $T = new bTemplate('manage', 'plays_item_ro.tpl');
        $T->assign('play', $play);
        $T->run();
    }else{
        $T = new bTemplate('manage', 'plays_item.tpl');
        $T->assign('play', $play);
        $T->run();
    }
}
