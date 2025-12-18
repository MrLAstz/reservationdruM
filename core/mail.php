<?php

    $mail_primary_sender = 'Nateetorn.Boonsup@gmail.com';
    $mail_primary_sender_name = 'Mail Service';

    function mail_ex($mail_to, $subject, $message)
    {   global $mail_primary_sender, $mail_primary_sender_name;
        
        $M = Get_Mailer();
        $M->From = $mail_primary_sender;
        $M->FromName = $mail_primary_sender_name;
        $M->addAddress($mail_to);
        $M->isHTML(false); 

        $M->Subject = $subject;
        $M->Body    = $message;
        $M->AltBody = strip_tags( $message );

        $res = Mailer_Send($M);   
        if (!$res)
            return $M->ErrorInfo;
        return true;
    }

?>