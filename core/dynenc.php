<?php

    function encrypt_password($text) 
    {   global $Configs;

        /*$key = $Configs["DATABASE"];
        if ($key == '')*/
            $key = "ims";     
        return hash_hmac('sha256', $text, $key, false);
    }

    function generate_random_password( $length = 8, $caps = true, $digits = true, $specials = true ) {
        $chars = "abcdefghijklmnopqrstuvwxyz";
        if ($caps)
            $chars .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if ($digits)
            $chars .= "0123456789";
        if ($specials)
            $chars .= "!@#$%^&*()_-=+;:,.?";
        $password = substr( str_shuffle( $chars ), 0, $length );
        return $password;
    }

    function generate_access_token( $text, $key )
    {        
        return hash_hmac('sha256', $text, $key, false);
    }

?>