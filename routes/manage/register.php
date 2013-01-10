<?PHP

$T = new bTemplate('manage', 'register.tpl');

if(isset($_POST['email'])){
    if($_POST['email'] != '' && $_POST['password'] != '' && $_POST['name_first'] != '' && $_POST['name_last'] != '' && $_POST['phone'] != ''){
        if(user::create($_POST['email'], $_POST['password'], $_POST['name_first'], $_POST['name_last'], $_POST['phone'])){
            header('location: /login');
        }else{
            $T->assign('error', 'User already exists, please try logging in.');
        }
    }else{
        $T->assign('error', 'Please fill out all information');
    }
}

$T->run();