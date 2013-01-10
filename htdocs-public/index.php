<?PHP
require_once('../include/config.inc.php');

$PATH = explode('/', strtolower(trim(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '', '/')));

switch($PATH[0]){
    // public pages, no logic
     case '':
        $PATH[0] = 'index';
    case 'index':
    case 'tour':
    case 'join':
    case 'howitworks':
    case 'support':
    case 'tos':
    case 'blog':
    case 'about':
    case 'privacy':
    case 'payment_processed':
    case 'signup_complete':
    case 'suspended':
        $T = new bTemplate('public', $PATH[0] . '.tpl');
        $T->run();
        break;
    // public pages with logic
    case 'contact':
    case 'login':
    case 'logout':
    case 'signup':
    case 'billing':
    case 'contact_info':
    case 'lostpassword':
    case 'event': // Special case so we can allow public events
        require_once('routes/public/'. $PATH[0] .'.php');
        break;
    case 'notusedatall': // login but no logic
        if(!isset($_SESSION['user'])){
            header('location: /login');
            die();
        }
        $T = new bTemplate('public', $PATH[0] . '.tpl');
        $T->run();
        break;
    // requires login
    case 'home':
    case 'events':
    case 'myaccount':
    case 'addaccount':
    case 'addusers':
        if(!isset($_SESSION['user'])){
            header('location: /login');
            die();
        }
        require_once('routes/public/'. $PATH[0] .'.php');
        break;
    case 'ajax':
        if(!isset($_SESSION['user'])){
            header('location: /login');
            die();
        }
        require_once('routes/public/ajax_'. $PATH[1] .'.php');
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        $T = new bTemplate('public', '404.tpl');
        $T->run();
        break;
}
