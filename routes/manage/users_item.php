<?PHP

$user = new user($PATH[1]);

if(isset($_POST['action'])){
    switch($_POST['action']){
        case 'save':
            if(isset($_POST['new_password']) && $_POST['new_password'] != '') $_POST['password'] = $_POST['new_password'];
            $_POST['notify_email'] = isset($_POST['notify_email']) ? 1 : 0;
            $user->save($_POST);
            header('location: /users/' . $user->id . '/'. prettyurlencode($user->name_first));
            die();
            break;
        case 'delete':
            $user->delete();
            header('location: /users');
            die();
            break;
        case 'restore':
            $user->restore();
            header('location: /users/' . $user->id . '/'. prettyurlencode($user->name_first));
            die();
            break;
    }
}

// display the page
if(getBestSupportedMimeType(array('text/html', 'application/json')) == 'application/json'){
    bTemplate::jsonResponse($users->resultsjson($filter, $currentpage));
}else{
    // fallback to html
    $T = new bTemplate('manage', 'users_item.tpl');
    $T->assign('user', $user);

    $T->run();
}
