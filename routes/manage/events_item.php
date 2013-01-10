<?PHP

$event = new event($PATH[1]);

if(isset($_POST['action'])){
    switch($_POST['action']){
        case 'save':
            $_POST['event_start'] = date('Y-m-d H:i:s', strtotime($_POST['event_start'] . ' ' . $_POST['event_start_time']));
            $_POST['event_end'] = date('Y-m-d H:i:s', strtotime($_POST['event_end'] . ' ' . $_POST['event_end_time']));
            $event->save($_POST);
            header('location: /events/' . $event->id . '/'. prettyurlencode($event->event_name));
            die();
            break;
        case 'delete':
            $event->delete();
            header('location: /events');
            die();
            break;
        case 'restore':
            $event->restore();
            header('location: /events/' . $event->id . '/'. prettyurlencode($event->event_name));
            die();
            break;
        case 'im_coming':
            $event->participant_response($_SESSION['user']->id, $_POST['coming']);
            header('location: /events/' . $event->id . '/'. prettyurlencode($event->event_name));
            die();
            break;
        case 'vote_game':
            $event->vote($_SESSION['user']->id, $_POST['game_fk']);
            header('location: /events/' . $event->id . '/'. prettyurlencode($event->event_name));
            die();
            break;
        case 'unvote_game':
            $event->unvote($_SESSION['user']->id, $_POST['game_fk']);
            header('location: /events/' . $event->id . '/'. prettyurlencode($event->event_name));
            die();
            break;
        case 'send_invite':
            $event->send_invite($_POST['invite_type']);
            header('location: /events/' . $event->id . '/'. prettyurlencode($event->event_name));
            die();
            break;
    }
}

// display the page
if(getBestSupportedMimeType(array('text/html', 'application/json')) == 'application/json'){
    bTemplate::jsonResponse($event->resultsjson($filter, $currentpage));
}else{
    // fallback to html
    if($event->isnew() || $event->is_coordinator($_SESSION['user']->id)){
        $T = new bTemplate('manage', 'events_item.tpl');
        $T->assign('event', $event);
        $T->run();
    }else{
        $T = new bTemplate('manage', 'events_item_ro.tpl');
        $T->assign('event', $event);
        $T->run();
    }
}
