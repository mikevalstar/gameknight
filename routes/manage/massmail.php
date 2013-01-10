<?PHP

if(isset($_POST['subject'])){
    email::send_all($_POST['subject'], $_POST['message']);
}

$T = new bTemplate('manage', 'massmail.tpl');
$T->run();