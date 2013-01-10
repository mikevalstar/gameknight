<?PHP

class bTemplate extends Smarty{
    var $tmpl = '';

    function __construct($site, $template){
        parent::__construct();
        
        $this->setTemplateDir(SITE_DIR . '/template/' . $site . '/');
        $this->setCompileDir(SITE_DIR . '/tmp/' . $site . '/compile/');
        $this->setCacheDir(SITE_DIR . '/tmp/' . $site . '/cache/');
        
        //$this->debugging = true;
        
        $this->tmpl = $template;
    }
    
    function run(){
        if(isset($_SESSION['user'])){
            $this->assign('U', $_SESSION['user']);
        }else{
            $this->assign('U', false);
        }
        $this->display($this->tmpl);
    }
    
    static function jsonResponse($json){
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($json);
    }
    
    function calendar($now){

        $year = date('Y', $now); 
        $month = date('m', $now); 
        
        /** 
         * want to start on sunday? use this array AND ( important! ) set $day_offset to 0 ( zero ) 
         * $days = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday'); 
         */ 
        $days = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday'); 
        $months = array('','january','febuary','march','april','may','june','july','august','september','october','november','december'); 
        
        // day offset, 1 is monday, 0 is sunday 
        $day_offset = 1; 
        
        $start_day = gmmktime ( 0, 0, 0, $month, 1, $year ); 
        $start_day_number = date ( 'w', $start_day ); 
        $days_in_month = date ( 't', $start_day ); 
        $row = 0; 
        $cal = array(); 
        $trow = 0; 
        $blank_days = $start_day_number - $day_offset; 
        
        if ( $blank_days < 0 ) { 
           $blank_days = 7 - abs ( $blank_days ); 
        } 
        
        for ( $x = 0 ; $x < $blank_days ; $x++ ) { 
           $cal[ $row ][ $trow ]['num'] = null; 
           $trow++; 
        } 
        
        for ( $x = 1 ; $x <= $days_in_month ; $x++ ) { 
            
           if ( ( $x + $blank_days - 1 ) % 7 == 0 ) { 
              $row++; 
           } 
           $cal[ $row ][ $trow ]['num'] = $x; 
           $cal[ $row ][ $trow ]['ts'] = mktime ( 0, 0, 0, $month, $x, $year ); 
           $trow++; 
        } 
        while ( ( ( $days_in_month + $blank_days ) % 7 ) != 0 ) { 
           $cal[ $row ][ $trow ]['num'] = null; 
           $days_in_month++; 
           $trow++; 
        } 
        
        $this->assign('months',$months); 
        $this->assign('days',$days); 
        $this->assign('cal',$cal); 
        $this->assign('month',abs($month)); 
        $this->assign('year',$year); 
    }
}