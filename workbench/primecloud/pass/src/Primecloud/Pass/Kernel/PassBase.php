<?php

namespace Primecloud\Pass\Kernel;

class PassBase
{
    private $sp = "\r\n"; //这里必须要写成双引号
    private $protocol = 'HTTP/1.1';
    private $port = '13232';
    private $requestLine = "";
    private $requestHeader = "";
    private $requestBody = "";
    private $requestInfo = "";
    private $fp = null;
    private $urlinfo = null;
    private $header = array();
    private $body = "";
    private $responseInfo = "";
    private static $http = null; //Http对象单例
    private $url;

    public static function create() {
        if (self::$http === null) {
            self::$http = new PassBase();
        }
        return self::$http;
    }
    
    public function init($url)
    {
        $this->url = $url;
        $this->parseurl($url);
        $this->header['Host'] = $this -> urlinfo['host'];
        return $this;
    }

    //user-Action拼接中需要cookie的值
    public function GetUriUser()
    {
        //dd(\Cookie::get('laravel_session'));
//        if (!isset($_COOKIE['sessionid'])) {
        if (! \Cookie::get('sessionid')) {
            return "";
        } else {
            return ":".\Cookie::get('sessionid');
        }
    }

    public function get($header = [])
    {
        $this->header = array_merge($this->header,$header);
        return $info = $this -> request('GET');
    }
     
    public function post($header = [], $body = []) 
    {
        $this->header = array_merge($this->header, $header);
            if (!empty($body)) {
                $this->body = http_build_query($body);
                $this->header['Content-Type'] = 'application/x-www-form-urlencoded';
                $this->header['Content-Length'] = strlen($this->body);
            }
        return $info = $this -> request('POST', $this->body);
    }
    
    //利用curl进行通信
    public function request($method, $data = null, $filePath = null)
    {
        try {
            $headers = "";
            $exits1 = array_key_exists('path',$this->urlinfo) ? $this->urlinfo['path'] : '/';
            $exits2 = isset($this->urlinfo['query']) ? $this->urlinfo['query'] : '';
            $this->requestLine = $method .' '. $exits1 .'?'. $exits2 .' '. $this->protocol;

            $headers = array();
            foreach ($this->header as $key => $value) {
                $headers[] = $key.':'.$value;
            }

            array_push($headers,$this->requestLine);
            $port = isset($this->urlinfo['port']) ? ($this->urlinfo['port']) : $this->port;
            
            $ch = curl_init($this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 500);                      //设置一个长整形数，作为最大延续多少秒。
            curl_setopt($ch, CURLOPT_PORT, $port);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_HEADER, 1);                        //取得返回头信息  
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);             //设置头信息的地方  
            if ($filePath) {
                $data['file'] = "@$filePath";
            }
            if ($data) {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POST, TRUE);                  //如果你想PHP去做一个正规的HTTP POST，设置这个选项为一个非零值。这个POST是普通的 application/x-www-from-urlencoded 类型，多数被HTML表单使用。
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);           //传递一个作为HTTP “POST”操作的所有数据的字符串
            }
            $res = curl_exec($ch);

            curl_close($ch);

            if (empty($res)) {
                return false;
            } else {
                $arr = explode("\r\n\r\n", $res);
                $array = json_decode($arr[1], true);
                if ($array["code"] == 300) {
                     return $this->transfer("http://".$array['data']['HostName'].":".$array['data']['Port'].$exits1."?".$exits2,$method);
                } else {
                     return $array; 
                }
            }
        } catch (\Exception $e) {
             throw new \Exception($e->getMessage());
        }
    }

    public function transfer($reurl, $method)
    {
       $this->init($reurl);
       return  $this->request($method);
    }
    
    private function parseurl($url)
    {
        $this->urlinfo = parse_url($url);
    }

}
    
?>