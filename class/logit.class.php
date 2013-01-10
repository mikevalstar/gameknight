<?PHP

class logit{
    static function logWeb(){
        $numargs = func_num_args();
        $arg_list = func_get_args();
        logit::_log(LOGIT_WEB, $arg_list, $numargs);
    }

    static function logCron(){
        $numargs = func_num_args();
        $arg_list = func_get_args();
        logit::_log(LOGIT_CRON, $arg_list, $numargs);
    }

    static function logCommon(){
        $numargs = func_num_args();
        $arg_list = func_get_args();
        logit::_log(LOGIT_COMMON, $arg_list, $numargs);
    }
    
    static function log(){
        $numargs = func_num_args();
        $arg_list = func_get_args();
        logit::_log(LOGIT_COMMON, $arg_list, $numargs);
    }
    
    static function _log($file, $arg_list, $numargs){
        if($numargs == 1){
            logit::_writeline($file, date('c') . ' - ' . $arg_list[0]);
        }else{
            logit::_writeline($file, date('c') . ' - ' . $arg_list[0]);
            for ($i = 1; $i < $numargs; $i++) {
                $arg = $arg_list[$i];
                if(is_array($arg)) $arg = str_replace("\n", "\n    ", print_r($arg, true));
                logit::_writeline($file, "    " . $arg);
            }
        }
    }
    
    static function _writeline($file, $str){
        $fp = fopen($file, 'a');
        fwrite($fp, $str . "\n");
        fclose($fp);
    }
}