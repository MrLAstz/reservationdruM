<?php

    function FormatHtml($Text)
    {
        $Text = str_replace(array("\r\n", "\r", "\n"), "<br/>", $Text);
        //$Text = htmlentities($Text, ENT_QUOTES, 'UTF-8' );
        return $Text;
    }


    function FormatDate($D) 
    {
        if (is_string($D)) {
            $D = strtotime($D);
        }        
        if (is_numeric($D))
            return date('d/m/', $D) . FormatYear($D);
        return '-';
    }

    function FormatDateTime($D, $ShowTime = true) 
    {
        if (is_string($D)) {
            $D = strtotime($D);
        }        
        if (is_numeric($D))
            return date('d M ', $D) . FormatYear($D) . (($ShowTime) ? date(' H:i', $D) : '');
            //return utf8_encode(strftime('%d %A %Y %H:%M', $D));
        return $D;
    }

    function FormatDateFull($D) 
    {
        if (is_string($D)) {
            $D = strtotime($D);
        }        
        if (is_numeric($D))
            return date('d M ', $D) . FormatYear($D);
            //return utf8_encode(strftime('%d %A %Y %H:%M', $D));
        return $D;
    }

    function FormatTime($D) 
    {
        if (is_string($D)) {
            $D = strtotime($D);
        }        
        if (is_numeric($D))
            return date('H:i', $D);
        return $D;
    }

    function FormatYear($D)
    {
        return (date('Y', $D));
    }

    function FormatYearThai($D)
    {
        return (date('Y', $D) + 543);
    }


    function FormatMoney($M)
    {
        return number_format( floatval( $M ), 2);
    }


?>