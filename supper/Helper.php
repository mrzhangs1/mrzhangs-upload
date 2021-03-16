<?php
/**
 * 助手函数
 * User: mrzhangs
 * Date: 2021/3/12
 * Time: 15:03
 */

if (!function_exists('format_bytes')) {

    /**
     * 将字节转换为可读文本
     * @param int    $size      大小
     * @param string $delimiter 分隔符
     * @return string
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 6; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $delimiter . $units[$i];
    }
}

if (!function_exists('back')) {
    /**
     * 请求错误数据返回
     * @param $error_code Integer 错误码
     * @param $msg String 错误信息
     * @param $option  Array  扩展信息
     */
    function back($error_code = 0,$msg = 'success',$option = null)
    {
        if($option !== null)
        {
            return ['code'=>$error_code,'msg'=>$msg,'option'=>$option];
        }
        return ['code'=>$error_code,'msg'=>$msg];
    }
}

if (!function_exists('throwError')) {
    /**
     * 异常抛出方法封装
     * @param $err_code
     * @param $http_code
     * @param string $msg
     */
    function throwError($error_code,$msg = 'error',$http_code = 200)
    {
        header('Content-Type:application/json; charset=utf-8');
        response_header($http_code);
        exit(json_encode(back($error_code,$msg),JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('response_header')) {
    /**
     *错误代码对应的消息头
     *@param $num 错误代码
     *@return header 消息头
     */
    function response_header($num)
    {
        $http = array (
            100 => "HTTP/1.1 100 Continue",
            101 => "HTTP/1.1 101 Switching Protocols",
            200 => "HTTP/1.1 200 OK",
            201 => "HTTP/1.1 201 Created",
            202 => "HTTP/1.1 202 Accepted",
            203 => "HTTP/1.1 203 Non-Authoritative Information",
            204 => "HTTP/1.1 204 No Content",
            205 => "HTTP/1.1 205 Reset Content",
            206 => "HTTP/1.1 206 Partial Content",
            300 => "HTTP/1.1 300 Multiple Choices",
            301 => "HTTP/1.1 301 Moved Permanently",
            302 => "HTTP/1.1 302 Found",
            303 => "HTTP/1.1 303 See Other",
            304 => "HTTP/1.1 304 Not Modified",
            305 => "HTTP/1.1 305 Use Proxy",
            307 => "HTTP/1.1 307 Temporary Redirect",
            400 => "HTTP/1.1 400 Bad Request",
            401 => "HTTP/1.1 401 Unauthorized",
            402 => "HTTP/1.1 402 Payment Required",
            403 => "HTTP/1.1 403 Forbidden",
            404 => "HTTP/1.1 404 Not Found",
            405 => "HTTP/1.1 405 Method Not Allowed",
            406 => "HTTP/1.1 406 Not Acceptable",
            407 => "HTTP/1.1 407 Proxy Authentication Required",
            408 => "HTTP/1.1 408 Request Time-out",
            409 => "HTTP/1.1 409 Conflict",
            410 => "HTTP/1.1 410 Gone",
            411 => "HTTP/1.1 411 Length Required",
            412 => "HTTP/1.1 412 Precondition Failed",
            413 => "HTTP/1.1 413 Request Entity Too Large",
            414 => "HTTP/1.1 414 Request-URI Too Large",
            415 => "HTTP/1.1 415 Unsupported Media Type",
            416 => "HTTP/1.1 416 Requested range not satisfiable",
            417 => "HTTP/1.1 417 Expectation Failed",
            500 => "HTTP/1.1 500 Internal Server Error",
            501 => "HTTP/1.1 501 Not Implemented",
            502 => "HTTP/1.1 502 Bad Gateway",
            503 => "HTTP/1.1 503 Service Unavailable",
            504 => "HTTP/1.1 504 Gateway Time-out"
        );
        header($http[$num]);
    }
}