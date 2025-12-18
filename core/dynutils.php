<?php
    
    $MakeSelectClass = "form-control";

	function MakeSelect( $Name, $Ops, $Value, $Def = null )
	{   global $MakeSelectClass;
		$Res = "<select name=\"$Name\" id=\"$Name\" class=\"$MakeSelectClass\">";
        if ( is_array($Ops) )
        {
            foreach($Ops as $K => $V)
            {					
                $Def = ($Value == $K) ? ' selected' : '';
                $Res .= "<option value=\"{$K}\"{$Def}>{$V}</option>";
            }
        }
        $Res .= "</select>";
		return $Res;
	}
	

	function MakeSelectTable( $Name, $Tb, $ColValue, $ColName, $DefValue = null, $AddEmpty = "---" )
	{   global $MakeSelectClass;
     
		$Res = "<select name=\"$Name\" id=\"$Name\" class=\"$MakeSelectClass\">";
     
        if ($AddEmpty !== false) 
        {
            $Def = ($DefValue == '') ? " selected" : "";
            $Res .= "<option value=\"\" {$Def}>{$AddEmpty}</option>";
        }
        if ( is_array($Tb) )
		foreach( $Tb as $Tr )
		{
			$Value = $Tr[$ColValue];
			$Name = $Tr[$ColName];
			
			$Def = ($Value == $DefValue) ? " selected" : "";

			$Res .= "<option value=\"{$Value}\" {$Def}>{$Name}</option>";
		}
		
		$Res .= "</select>";
		return $Res;
	}

    function MakeLink($Href, $Text = null, $Target = null, $Title = null) 
    {
        if (!$Text)
            $Text = $Href;
        if (!$Title)
            $Title = $Text;
        
        return "<a href=\"{$Href}\" \"title={$Title}\">{$Text}</a>";
    }

    function MakeLinkEmail($Email, $Text = null, $Target = null, $Title = null)
    {
        if ($Email != '')
            $Href = "mailto:{$Email}";
        else
        {
            $Href = '#';
            $Text = '&nbsp;';
        }
        if (!$Text)
            $Text = $Email;
        return MakeLink($Href, $Text, $Target, $Title);
    }

    function MakeImage($Url, $W = '', $H = '', $Class = '') 
    {
        if ($Url != '') 
        {
            $res = "<img src=\"{$Url}\" ";
            if ($W != '')
                $res .= "width=\"{$W}\" ";
            if ($H != '')
                $res .= "height=\"{$H}\" ";
            if ($Class != '')
                $res .= "class=\"{$Class}\" ";
            return $res . " />";
        }
        return "";
    }

    function SqlDateTime($Timestamp = null)
    {
        if ($Timestamp == null)
            $Timestamp = time();
        if (is_string($Timestamp))
            $Timestamp = ParseDateTimeThai(str_replace('/', '-', $Timestamp));        
        //    $Timestamp = strtotime(str_replace('/', '-', $Timestamp));        
        return date('Y-m-d H:i:s', $Timestamp);
    }

    function SqlParseDate($Text, $Time = '')
    {
        if ($Time != '')
            $Text .= str_replace(".", ":", " $Time");
        $Timestamp = ParseDateTimeThai(str_replace('/', '-', $Text));
        //$Timestamp = strtotime(str_replace('/', '-', $Text));
        if ($Timestamp === false)
            return null;
        return SqlDateTime($Timestamp);            
    }

    function ParseDateTimeThai($Text) 
    {
        $result = preg_match_all('/^([0-3]?\d)-([0-1]?\d)-(2\d\d\d)(\s(\d{1,2}):(\d{2}))?$/', $Text, $matches);
        $d = intval($matches[1][0]);
        $m = intval($matches[2][0]);
        $y = intval($matches[3][0]);
        $h = intval($matches[5][0]);
        $i = intval($matches[6][0]);
        if (($d == 0) || ($m == 0) || ($y < 1900))
            return false;
        if (($d > 31) || ($m > 12))
            return false;
        if ($y > 2543)
            $y -= 543;
        $Time = mktime($h, $i, $s, $m, $d, $y);
        return $Time;
        //$result = date('d/M/Y h:i:s', $Time);        
    }

    function ParseDateTime($Text) 
    {
        return ParseDateTimeThai(str_replace('/', '-', $Text)) ;
    }
	
    function TableArrayToArrayList($Tb, $ColName, $ColValue, $Initial = array('' => ''))
    {
        $res = $Initial;
        foreach($Tb as $Tr) {
            $Name = $Tr[$ColName];
            if ($Name != '')
                $res[$Name] = $Tr[$ColValue];
        }
        return $res;
    }

	/* Input Data Functions */
	function ClearMagicQuote( $Inp )
	{
		if (get_magic_quotes_gpc() > 0)
			$Inp = stripslashes ( $Inp );
		return $Inp;
	}
	
	function ClearMagicQuoteArray( $Inps )
	{
		if (is_array($Inps))
		{	
			$Res = $Inps;		
			foreach ($Res as & $Val)
			{		
				$Val = ClearMagicQuote($Val);
			}
			return $Res;
		}
		return $Inp;
	}

	function ReadPOST( $V, $D = "" )
	{		
		return ClearMagicQuote( isset( $_POST[ $V ] ) ? $_POST[ $V ] : $D );
	}

	function ReadGET( $V, $D = "" )
	{		
		return ClearMagicQuote( isset( $_GET[ $V ] ) ? $_GET[ $V ] : $D );
	}
	
	function ReadCOOKIE( $V, $D = "")
	{
		return ClearMagicQuote( isset( $_COOKIE[ $V ] ) ? $_COOKIE[ $V ] : $D );
	}

	function ReadSERVER( $V, $D = "")
	{
		return isset( $_SERVER[ $V ] ) ? $_SERVER[ $V ] : $D ;
	}
	
	
	/* Utility Function */
    if (!function_exists("stripos")) {
        function stripos($str, $needle, $offset=0)
       {
           return strpos(strtolower($str), strtolower($needle), $offset);
       }
   	} 
   	

    if (!function_exists("str_ireplace")) 
    {
        function str_ireplace($search, $replace, $subject)
       {
           return str_replace(strtolower($search), strtolower($replace), $subject);
       }
    } 
   
   	if (!function_exists("scandir"))
   	{
       function scandir($dir, $sort = 0)
       {
            if (! is_dir($dir))
                return NULL;        

            $dh  = opendir($dir);
			while (false !== ($filename = readdir($dh))) {
			   $retfiles[] = $filename;
			}
			closedir($dh);
			sort($retfiles);
			return $retfiles;
		}
    }
    
    function IsStrEmpty($Src)
    {
    	return strlen( trim($Src) ) <= 0;
    }
    
    function IsStrNotEmpty($Src)
    {
    	return strlen( trim($Src) ) > 0;
    }
    
    function IsInString($Src, $Needle)
    {
    	return ( strpos($Src, $Needle) !== false );
    }
    
    /*  GetStrCombineParams - Combine Parameter  
     * 
     *  Format Example:    $PrefV $Value $SuffV $Spr ...
     * 						(aaaa),(bbbb),(ccccc) 
     * 
     */
    
	function GetStrCombineParams($Params, $PrefV, $SuffV, $Spr, $StIndex = 0, $Inc = 1, $Max = 0)
	{
		if ($Max > 0)
			$MaxParams = $Max;
		else 
			$MaxParams = count($Params);		
		$Res = "";
		$Index = $StIndex;
		while($Index < $MaxParams)
		{
			$Res .= $PrefV . $Params[$Index] . $SuffV;
			$Index += $Inc;			
			if ($Index < $MaxParams)
				$Res .= $Spr;			
		}
		return $Res;
	}
	
	/*  GetStrCombineKVParams - Combine Key-Value Pair Parameter
	 * 
	 *  Format Example:      $PrefK $Key $SuffK $Mid $PrefV $Value $SuffV $Spr ...
     * 						[keyaaa]=(valueaaa), [keybbb]=(valuebbb), [keyccc]=(valueccc) 
	 * 	 				
	 */
	
	function GetStrCombineKVParams($Params, $PrefK, $SuffK, $Middle, $PrefV, $SuffV, $Spr, $StIndex = 0, $Max = 0)
	{
		if ($Max > 0)
			$MaxParams = $Max;
		else 
			$MaxParams = count($Params);		
		$Res = "";
		$Index = $StIndex;
		while($Index + 2 <= $MaxParams)
		{
			$Res .= $PrefK . $Params[$Index] . $SuffK;			
			$Index++;
			$Res .= $Middle . $PrefV . $Params[$Index] . $SuffV;			
			$Index++;
			if ($Index + 2 <= $MaxParams)
				$Res .= $Spr;			
		}
		return $Res;
	}
	
	/*  GetStrCombineParamsArgs - Combine Parameter by Function Arguments
	 * 
	 *  Format Example:      $PrefK $Key $SuffK $Mid $PrefV $Value $SuffV $Spr ...
     * 						aaaa , bbb , cccc 
	 * 	 				
	 */
	
	function GetStrCombineParamsArgs($Spr)
	{
		$Res = "";
		$Index = 1;
		$Args = func_get_args();		
		$ArgsCount = count($Args);		
		while($Index < $ArgsCount)
		{
			if ($Args[$Index++])
			{
				if ($Res != "")
					$Res .= $Spr;
				$Res .= $Args[$Index];
			}
			$Index++;
		}
		return  $Res;
	}
	
	function GetStrBefore($Src, $Before, $Inc = false, $CaseSen = false)
    {
    	if ($CaseSen)
    		$Pos = stripos($Src, $Before);
    	else
    		$Pos = strpos($Src, $Before);
    	    	 
    	if ($Pos !== false)
    	{
    		if ($Inc)
    		{
    	   		$BeforeLen = strlen($Before);
    	   		return substr($Src, 0, $Pos + $BeforeLen);
    		}
    		
    		return substr($Src, 0, $Pos);    		    	    
    	}
    	return "";
    }

    function GetStrAfter($Src, $After, $Inc = false, $CaseSen = false)
    {
    	if ($CaseSen)
    		$Pos = stripos($Src, $After);
    	else
    		$Pos = strpos($Src, $After);
    	    	 
    	if ($Pos !== false)
    	{
    		if (!$Inc)
    		{
    	   		$AfterLen = strlen($After);
    	   		return substr($Src, $Pos + $AfterLen);
    		}
    		
    		return substr($Src, $Pos  );    		    	    
    	}
    	return "";
    }
    
    function GetStrBetween($Src, $StrSt, $StrEn, $Inc = false, $CaseSen = false, $Def = "")
    {
    	if ($CaseSen)
    	{
    		$PosSt = stripos($Src, $StrSt);
   			$PosEn = stripos($Src, $StrEn, $PosSt);
    	}
    	else
    	{
    		$PosSt = strpos($Src, $StrSt);
   			$PosEn = strpos($Src, $StrEn, $PosSt);
    	}
    	
    	if (($PosSt === false) && ($PosEn === false))
    		return $Def;
    	
    	if ($PosEn === false)
    		$PosEn = strlen($Src);

    	if (!$Inc)
    	{
    		if ($PosSt === false)
    			$PosSt = 0;
    		else
    		{
    			$StrStLen = strlen($StrSt);
    			$PosSt += $StrStLen;
    		}
    	}
    	else
    	{
    		$StrEnLen = strlen($StrEn);
    		$PosEn += $StrEnLen;
    	}
    	
    	return substr($Src, $PosSt, $PosEn - $PosSt);
    	
    }
    
    function GetStrBetweenCross($Src, $StrSt, $StrEn, $Inc = false, $CaseSen = false, $Def = "")
    {
    	if ($CaseSen)
    	{
    		$PosSt = stripos($Src, $StrSt);
   			$PosEn = stripos($Src, $StrEn, $PosSt);
    	}
    	else
    	{
    		$PosSt = strpos($Src, $StrSt);
   			$PosEn = strrpos($Src, $StrEn, $PosSt);
    	}
    	
    	if (($PosSt === false) && ($PosEn === false))
    		return $Def;
    	
    	if ($PosEn === false)
    		$PosEn = strlen($Src);

    	if (!$Inc)
    	{
    		if ($PosSt === false)
    			$PosSt = 0;
    		else
    		{
    			$StrStLen = strlen($StrSt);
    			$PosSt += $StrStLen;
    		}
    	}
    	else
    	{
    		$StrEnLen = strlen($StrEn);
    		$PosEn += $StrEnLen;
    	}
    	
    	return substr($Src, $PosSt, $PosEn - $PosSt);    	
    }
    
    /* General Functions */
	function Microtime_Begin()
	{
		return Microtime_GetFloat();		
	}
		
	function Microtime_GetFloat()
	{
    	list($usec, $sec) = explode(" ", microtime(true));
    	return ((float)$usec + (float)$sec);
	}
	
	function Microtime_CatchResult($StTime)
	{
		$EnTime = Microtime_GetFloat();
		return $EnTime - $StTime;		
	}
	
	
    function DefDefault( $V, $D )
	{
		return (isset( $V )) ? $V : $D;
	}

	function ConvLink( $V, $A, $T = "" )
	{
		return "<A " . $T . " HRef=\"$V\">$A</A>";
	}
	
	function GetStrIden( $V )
	{
		$CurVal = 0;
		$Result = 0;
		$MaxLen = strlen( $V );
		
		if ($MaxLen > 0)
		{
            for ($Cnt = 0; $Cnt < $MaxLen; $Cnt++)
            {
                $CurVal = ord( $V[ $Cnt ] );
                $Result ^= ((($Result << 3) * $CurVal) + $CurVal);

            }
            $Result *= $MaxLen;
		}
 		return $Result;		
	}

	function GetStrIdenAsHex( $V )
	{
		return sprintf("%08X", GetStrIden($V) );
	}
	
	/* File Name and Path  Functions */
	function MakeFilePath( $V )
	{
		$Res = $V;
		$VLen = strlen($V);
		if ($VLen > 0)
		{
			if ($V[ $VLen - 1 ] != "/")
		   	$Res .= "/";
		}
		return $Res;				
	}

	function CombineFilePath( $V, $X = "" )
	{
		return MakeFilePath( $V ) . $X ;
	}

	function GetFileName( $V )
	{
		$SpPos = strrpos( $V , "/");
		if ($SpPos !== false)
		return substr( $V, $SpPos + 1 );
		else
		return $V;
	}

	function GetFilePath( $V )
	{
		$SpPos = strrpos( $V , "/");
		if ($SpPos !== false)
		return substr( $V, 0, $SpPos );
		else
		return "";
	}
	
	function GetCurrentDateTime()
	{
		return date('Y-m-d H:i:s');
	}


	/* Convertion Functions */
	function EncQuote($Inp, $EncSingle = true, $EncDouble = true)
	{
		if ($EncDouble)
			$Inp = str_replace('"', "&quot;", $Inp ) ;
		if ($EncSingle)
			$Inp = str_replace("'", "&#039;",  $Inp) ;
		return $Inp;
	}
	
	function EncHtml($Inp)
	{
		return htmlspecialchars($Inp, ENT_QUOTES );
	}

	function EncValues( $Src)
	{
        $MaxArgs = func_num_args();
        if (($MaxArgs < 3) && (strlen( $Src ) == 0))
            return $Src;

        $Args = func_get_args();
        for ($CntArgs = 1; $CntArgs < $MaxArgs - 1; $CntArgs += 2 )
        {
            $FindStr = $Args [ $CntArgs ] ;
            $RepStr = $Args [ $CntArgs + 1 ] ;

            $Src = str_replace( $FindStr, $RepStr, $Src);
        }			
        return $Src;			
	}

	function tis2utf8($tis) 
	{
		for( $i=0 ; $i< strlen($tis) ; $i++ )
		{
            $s = substr($tis, $i, 1);
            $val = ord($s);
            if( $val < 0x80 ){
                $utf8 .= $s;
            } elseif ( ( 0xA1 <= $val and $val <= 0xDA ) or ( 0xDF <= $val and $val <= 0xFB ) ){
                $unicode = 0x0E00 + $val - 0xA0;
                $utf8 .= chr( 0xE0 | ($unicode >> 12) );
                $utf8 .= chr( 0x80 | (($unicode >> 6) & 0x3F) );
                $utf8 .= chr( 0x80 | ($unicode & 0x3F) );
            }
		}
        return $utf8;
	}
	
	function utf8_to_tis620($string) 
	{
        $str = $string;
        $res = "";
        for ($i = 0; $i < strlen($str); $i++) {
            if (ord($str[$i]) == 224) {
                $unicode = ord($str[$i+2]) & 0x3F;
                $unicode |= (ord($str[$i+1]) & 0x3F) << 6;
                $unicode |= (ord($str[$i]) & 0x0F) << 12;
                $res .= chr($unicode-0x0E00+0xA0);
                $i += 2;
            } else {
                $res .= $str[$i];
            }
        }
        return $res;
	}


?>