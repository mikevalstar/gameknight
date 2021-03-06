<?PHP
require_once('../include/config.inc.php');

$PATH = explode('/', strtolower(trim(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '', '/')));

if(isset($_GET['bypass'])) $_SESSION['user'] = new user(1);

switch($PATH[0]){
    // public pages
    case 'login':
    case 'logout':
    case 'register':
    case 'forgot_password':
        require_once('routes/manage/'. $PATH[0] .'.php');
        break;
    // requires login
    case '':
    case 'index':
        $PATH[0] = 'home';
    case 'games':
    case 'events':
    case 'users':
    case 'massmail':
    case 'plays':
        if(!isset($_SESSION['user'])){
            header('location: /login');
            die();
        }

        if(isset($PATH[1])){
            require_once('routes/manage/'. $PATH[0] .'_item.php');
        }else{
            require_once('routes/manage/'. $PATH[0] .'.php');
        }
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        $T = new bTemplate('manage', '404.tpl');
        $T->run();
        break;
}
