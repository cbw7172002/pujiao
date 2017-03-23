<?php

namespace Primecloud\Pass\Kernel;
header("Content-type:text/html;charset=utf-8");  
class PassBase
{
    private $sp = "\r\n";   //  这里必须要写成双引号
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
    static private $http = null; //Http对象单例
     
    private function __construct() 
    {

    }
     
    static public function create() 
    {
        if (self::$http === null) { 
            self::$http = new PassBase();
        }
        return self::$http;
    }
     
    public function init($url) 
    {
        $this->parseurl($url);
        $this->header['Host'] = $this -> urlinfo['host'];
        return $this;
    }

    public function GetUriUser()
    {
        if (!isset($_COOKIE['sessionid'])) {
            return "";
        } else {
            return ":".$_COOKIE['sessionid'];
        }
    }

    public function get($header = []) 
    {   

        try {
            $this->header = array_merge($this->header, $header);
            return $this->request('GET');
        } catch (Exception $e) {   
            return false; 
        }  

    }
     
    public function post($header = [], $body = []) 
    {

        try {
            $this->header = array_merge($this->header, $header);
            if (!empty($body)) {
                $this->body = http_build_query($body);
                $this->header['Content-Type'] = 'application/x-www-form-urlencoded';
                $this->header['Content-Length'] = strlen($this->body);
            }
            return $this->request('POST');
        } catch (Exception $e) {   
            return false;
        }  
    }
     
    public function method()
    {
        return $this->request('GET');
    }

    private function geturlinfo($key)
    {
       return isset($this->urlinfo[$key]) ? $this->urlinfo[$key] : '';
    }

    private function request($method) 
    { 
       try{
            $header = "";
            $exits1 = array_key_exists('path',$this->urlinfo) ? $this->urlinfo['path'] : '/';
            $exits2 = isset($this->urlinfo['query']) ? $this->urlinfo['query'] : '';
            $this->requestLine = $method .' '. $exits1 .'?'. $exits2 .' '. $this->protocol;
            foreach ($this->header as $key => $value) {
                $header .= $header == "" ? $key .':'. $value : $this->sp.$key .':'. $value;
            }
            $this->requestHeader = $header.$this->sp.$this->sp;
            $this->requestInfo = $this->requestLine.$this->sp.$this->requestHeader;
            if ($this->body != "") {
                $this->requestInfo .= $this->body;
            }
            /*
             * 注意：这里的fsockopen中的url参数形式为"www.xxx.com"
             * 不能够带"http://"这种
             */
            
            $port = isset($this->urlinfo['port']) ? ($this->urlinfo['port']) : $this->port;
            $this->fp = @fsockopen($this->urlinfo['host'], $port, $errno, $errstr,10);

             
            if (!$this->fp) {
             //return $errstr.'('.$errno.')';
              return false;
            }
            if (fwrite($this->fp, $this->requestInfo)) {
              $str = "";
            while (!feof($this->fp)) {
              $str .= fread($this->fp, 1024);
            }
             $this->responseInfo = $str;
            }

            fclose($this->fp);

            $res = $this->responseInfo;
            if (empty($res)) {
             return false;
            } else {
                $arr = explode("\r\n\r\n",$res);
                $array = json_decode($arr[1],true);
                if ($array["code"] == 300) {
                    return $this->transfer("http://".$array['data']['HostName'].":".$array['data']['Port'].$exits1."?".$exits2,$method);
                } else {
                    return $array; 
                }
            }
        }catch(Exception $e){

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