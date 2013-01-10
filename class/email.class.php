<?PHP

class email{
    
    static function send($to, $to_name, $subject, $message, $messageHTML = false){
        $transport = Swift_MailTransport::newInstance();
        $mailer = Swift_Mailer::newInstance($transport);
        
        $message = Swift_Message::newInstance()

          // Give the message a subject
          ->setSubject($subject)
        
          // Set the From address with an associative array
          ->setFrom(array('no-reply@konradgroup.com' => 'KG Game Knight'))
        
          // Set the To addresses with an associative array
          ->setTo(array($to => $to_name))
        
          // Give it a body
          ->setBody($message . "\r\n\r\n ---- Because he's the hero Konrad Group deserves, but not the one it needs right now. So we'll hunt him. Because he can take it. Because he's not our hero. He's a silent guardian, a watchful protector. A game knight.", 'text/plain');
          
        if($messageHTML)
            $message->addPart($messageHTML, 'text/html');
            
        $mailer->send($message);
    }
    
    static function send_all($subject, $message, $messageHTML = false){
        $users = new userlist();
        $users = $users->results_all();
        
        $user_array = array();
        foreach($users as $v)
            $user_array[$v['email']] = $v['name_first'] . ' ' . $v['name_last'];
        
        $transport = Swift_MailTransport::newInstance();
        $mailer = Swift_Mailer::newInstance($transport);
        
        $message = Swift_Message::newInstance()

          // Give the message a subject
          ->setSubject($subject)
        
          // Set the From address with an associative array
          ->setFrom(array('no-reply@konradgroup.com' => 'KG Game Knight'))
        
          // Set the To addresses with an associative array
          ->setTo($user_array)
        
          // Give it a body
          ->setBody($message . "\r\n\r\n ---- Because he's the hero Konrad Group deserves, but not the one it needs right now. So we'll hunt him. Because he can take it. Because he's not our hero. He's a silent guardian, a watchful protector. A game knight.", 'text/plain');
          
        if($messageHTML)
            $message->addPart($messageHTML, 'text/html');
            
        $mailer->send($message);
    }
    
}