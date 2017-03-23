<?php
namespace App\commonApi\Messages;

class Messages {

    public $username = 'qich';
    public $password = 'asdf123';
    public $apikey = '1367af07e97c1ab5a3d1062055b09442';
    public $mobile;
    public $content;

    public function setInfo($phone,$content){
        $this->mobile = $phone;
        $this->content = $content;
    }

    public function sendMsg()
    {
        $url = 'http://m.5c.com.cn/api/send/?';
        $data = array (
            'username' => $this -> username,
            'password' => $this -> password,
            'mobile' => $this -> mobile,
            'content' => $this -> content,
            'apikey' => $this -> apikey,
        );
        $result = $this -> curlSMS($url, $data);

        $result = explode(":", $result);
        if ( $result[0] == "success" )
            return 2; //成功
        else
            return 0; //失败

    }

    public function curlSMS ($url, $post_fields = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_REFERER, 'http://www.yourdomain.com');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $data = curl_exec($ch);
        curl_close($ch);
        $res = explode("\r\n\r\n", $data);
        return $res[2];
    }

    public function createCode(){
        return str_shuffle(mt_rand(100, 999). "" .mt_rand(100, 999));
    }



}
