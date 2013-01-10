<?PHP

$game = new game($PATH[1]);

if(isset($_POST['action'])){
    switch($_POST['action']){
        case 'save':
            $_POST['coop'] = isset($_POST['coop']) ? 1 : 0;
            $_POST['team'] = isset($_POST['team']) ? 1 : 0;
            $_POST['preorder'] = isset($_POST['preorder']) ? 1 : 0;
            $game->save($_POST);
            header('location: /Games/' . $game->id . '/'. prettyurlencode($game->game_name));
            die();
            break;
        case 'delete':
            $game->delete();
            header('location: /Games');
            die();
            break;
        case 'restore':
            $game->restore();
            header('location: /Games/' . $game->id . '/'. prettyurlencode($game->game_name));
            die();
            break;
        case 'add_owner':
            if(isset($_POST['user_pk'])){
                $game->add_owner($_POST['user_pk']);
            }else{
                $game->add_owner_by_email($_POST['email']);
            }
            header('location: /Games/' . $game->id . '/'. prettyurlencode($game->game_name));
            die();
            break;
        case 'remove_owner':
            if(isset($_POST['user_pk'])){
                $game->remove_owner(false, $_POST['user_pk']);
            }else{
                $game->remove_owner($_POST['owner_pk']);
            }
            header('location: /Games/' . $game->id . '/'. prettyurlencode($game->game_name));
            die();
            break;
    }
}

// display the page
if(getBestSupportedMimeType(array('text/html', 'application/json')) == 'application/json'){
    bTemplate::jsonResponse($game->resultsjson($filter, $currentpage));
}else{
    // fallback to html
    $T = new bTemplate('manage', 'games_item.tpl');
    $T->assign('game', $game);
    $T->run();
}
