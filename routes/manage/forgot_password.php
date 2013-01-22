<?PHP

$T = new bTemplate('manage', 'forgot_password.tpl');

if(isset($_POST['email'])){
    if(user::lostpassword($_POST['email'])){
        header('location: /');
    }else{
        $T->assign('error', 'There is no use for this email address.');
    }
}

$T->run();