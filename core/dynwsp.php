<?php
 
    if (!isset($Configs['NOCACHE']))
        $Configs['NOCACHE'] = true;
    if (!isset($Configs['BUFFERING']))
        $Configs['BUFFERING'] = true;
    
    function WebService_Start()
    {   global $Configs, $_INPUT;
        $Configs['DEBUG'] = false;
     
        if ($Configs['NOCACHE'])
            WebService_Output_Cache(true);
     
        if ($Configs['BUFFERING']) 
            ob_start();
     
        $_INPUT = json_decode(file_get_contents('php://input'), true);
     
        header('Content-Type: application/json; charset=utf-8');     
    }

    function WebService_Input_Array($raw)
    {
        return json_decode($raw, true);
    }

    function WebService_Output_Cache($enabled)
    {
        if (!$enabled) {
            header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache"); 
        }
    }

    function WebService_Response($value, $data = null)
    {   global $Configs;
     
        if (is_numeric($value) && is_string($data))
        {
            WebService_Response_Code($value);
            $value = $data;
        }
     
        echo(json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ));
     
        if ($Configs['BUFFERING']) 
            ob_end_flush();
        exit;
    }

    function WebService_Redirect($url, $code = 302, $body = true) 
    {
        $location = trim($url);
        header( "Location: {$location}", true, $code );
        echo("<html><head><meta http-equiv=\"refresh\" content=\"0; URL='{$location}'\" /></head><body><script>window.location='{$url}'</script></body></html>");
    }

    function WebService_Response_Code($code) 
    {
        $code = intval($code);
        $http = array(
            100 => 'HTTP/1.1 100 Continue',
            101 => 'HTTP/1.1 101 Switching Protocols',
            200 => 'HTTP/1.1 200 OK',
            201 => 'HTTP/1.1 201 Created',
            202 => 'HTTP/1.1 202 Accepted',
            203 => 'HTTP/1.1 203 Non-Authoritative Information',
            204 => 'HTTP/1.1 204 No Content',
            205 => 'HTTP/1.1 205 Reset Content',
            206 => 'HTTP/1.1 206 Partial Content',
            300 => 'HTTP/1.1 300 Multiple Choices',
            301 => 'HTTP/1.1 301 Moved Permanently',
            302 => 'HTTP/1.1 302 Found',
            303 => 'HTTP/1.1 303 See Other',
            304 => 'HTTP/1.1 304 Not Modified',
            305 => 'HTTP/1.1 305 Use Proxy',
            307 => 'HTTP/1.1 307 Temporary Redirect',
            400 => 'HTTP/1.1 400 Bad Request',
            401 => 'HTTP/1.1 401 Unauthorized',
            402 => 'HTTP/1.1 402 Payment Required',
            403 => 'HTTP/1.1 403 Forbidden',
            404 => 'HTTP/1.1 404 Not Found',
            405 => 'HTTP/1.1 405 Method Not Allowed',
            406 => 'HTTP/1.1 406 Not Acceptable',
            407 => 'HTTP/1.1 407 Proxy Authentication Required',
            408 => 'HTTP/1.1 408 Request Time-out',
            409 => 'HTTP/1.1 409 Conflict',
            410 => 'HTTP/1.1 410 Gone',
            411 => 'HTTP/1.1 411 Length Required',
            412 => 'HTTP/1.1 412 Precondition Failed',
            413 => 'HTTP/1.1 413 Request Entity Too Large',
            414 => 'HTTP/1.1 414 Request-URI Too Large',
            415 => 'HTTP/1.1 415 Unsupported Media Type',
            416 => 'HTTP/1.1 416 Requested Range Not Satisfiable',
            417 => 'HTTP/1.1 417 Expectation Failed',
            500 => 'HTTP/1.1 500 Internal Server Error',
            501 => 'HTTP/1.1 501 Not Implemented',
            502 => 'HTTP/1.1 502 Bad Gateway',
            503 => 'HTTP/1.1 503 Service Unavailable',
            504 => 'HTTP/1.1 504 Gateway Time-out',
            505 => 'HTTP/1.1 505 HTTP Version Not Supported',
        );

        if (isset($http[$code]))
        {
            header($http[$code], true, $code);
            return true;
        }
 
        return false;
    }

    function WebService_GetAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * get access token from header
     * */
    function WebService_GetAuthorizationBearer()
    {
        $headers = WebService_GetAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }


    
?>
