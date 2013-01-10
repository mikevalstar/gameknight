<?PHP

$T = new bTemplate('manage', 'login.tpl');

if(isset($_POST['email'])){
    if(user::login($_POST['email'], $_POST['password'])){
        header('location: /');
    }else{
        $T->assign('email', $_POST['email']);
        $T->assign('error', 'Your user or password could not be found.');
    }
}

$T->run();