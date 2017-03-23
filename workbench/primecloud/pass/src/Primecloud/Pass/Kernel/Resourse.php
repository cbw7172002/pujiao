<?php

namespace Primecloud\Pass\Kernel;

use Primecloud\Pass\Kernel\PassBase as Http;
use Primecloud\Pass\Kernel\Config;
use Primecloud\Pass\Kernel\User;

class Resourse
{
	public $http;
	public $url;
    public $AppID;
    public $host;

    public function __construct()
    {
	    $this -> url = Config::Url;
		$this -> AppID = Config::AppID;
		$this -> host = $_SERVER['HTTP_HOST'];
    }

    
    /**
	    * 资源上传状态接口
	    * [
	    * "message"=> "OK",
	    * "data": [
	    *        "AllowUpload": 2,    //表示资源已经上传完成，此时无需上传/为1的话表示资源尚未传完但传输任务中断，此时允许上传
	    *		 "FileLenth": 107617611, //上传文件的大小
	    *		 "FileID": "934a8b9df8084d049e149829e09f1f8b", //该资源的在平台中的唯一ID号，为32位字符串。该值为服务器所生成。
	    *		 "UploadLength": 107617611,      //上传成功的大小
	    *		  "ExecuteCode": 1
	    *  ]
	    * "code"=> 200
    */
	public function getuploadstatus($pinurl)
	{
		$urls = $this -> url.$pinurl;
		$this -> http = Http::create() -> init($urls);
		$arr =  $this -> http -> get([
			'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@". $this -> host ."/Resource/GetUploadState",
			'Referer' => 'http://'. $this -> host .''
		]);
		return $arr;
	}


	/**
     * 资源下载接口
     *
     * @param fileid :该资源的在平台中的唯一ID号，为32位字符串。该值为服务器所生成。
     * @return 
     * [
     *     'message' => string 'OK'       //成功返回ok.错误返回error
     *     'data' => string 'http://10.10.201.200:13232/Resource/DownloadUrl?id=da271b41fc2b431ea26c85d6765e4ac7'  //获取到的下载地址
     *     'code' => int 200     //通信成功200
     * ]
     */
	public function getdownload($fileid)
	{
		$this -> http = Http::create() -> init($this -> url);
		$arr = $this -> http -> get([
			'User-Action' => "web://".$this->AppID.$this->http->GetUriUser()."@".$this->host."/Resource/GetDownloadUrl?FID=".$fileid,
			'Referer'=>"http://".$this->host.""
		]);
		return $arr;
	}


	/**
     * 资源转换接口
     *
     * @param fileid :该资源的在平台中的唯一ID号，为32位字符串。该值为服务器所生成。
     * @return 
     * [
     * 		"message": "OK",     		//成功返回ok.错误返回error
     * 		"data": [
     *      	"Type": "Video", 		//资源类型:目前包含Video, Audio, Document,NoSupport四种类型。
     *      	"Waiting": -1,   		//转换成功为1,0为转换中，1,2,3等代表前面等待的人数
     *      	"FileID": "324cd26d289c4532951aab9d56df3bf0",
     * 			"FileList": [			//转换结果集，一个资源可能被转换为多个质量的资源
     *    			[
     *      			"FileID": "b2d1aab958f345cfade37e1f15643074",
     *      			"Level": 1    	//级别1:360P
     *    			],	
     *    			[
     *      			"FileID": "c0783f5f9e514fec80e54fa9e231d8a1",
     *      			"Level": 2   	//级别2:720P
     *    			],
     *    			[
     *      			"FileID": "1078dc7d344b4b43af8e71ada7ed562d",
     *      			"Level": 3   	//级别3:1080P
     *    			]
     * 			],
     *    		"ExecuteCode": 1
     *   	],
     * 		"code": 200     			//通信成功
     * ]
     */
	public function transformation($pinurl)
	{

		$urls = $this -> url.$pinurl;
		$this -> http = Http::create() -> init($urls);
		$arr = $this -> http -> get([
			'User-Action' => "web://". $this->AppID.$this->http->GetUriUser()."@".$this->host."/Resource/Convert",
			'Referer' => "http://". $this -> host .""
		]);
		return $arr;
	}

	/**
     * 获取资源在线播放地址接口
     *
     * @param fileid :该资源的在平台中的唯一ID号，为32位字符串。该值为服务器所生成。
     * @return 
     * [
     *     'message' => string 'OK'       //成功返回ok.错误返回error
     *     "data": "http://10.10.201.200:13232/Resource/ OnlinePlayUrl?id=0d361c020c4e47e9b58f989a233940b2",  //获取到的播放地址
     *     'code' => int 200     //通信成功200
     * ]
     */
	public function clickplay($playfileid, $hosturl)
	{
		$this -> http = Http::create() -> init($this -> url);
		if (empty($hosturl)) {
			$arr = $this -> http -> get([
				'User-Action' => "web://".$this->AppID.$this->http->GetUriUser()."@".$this->host."/Resource/GetOnlinePlayUrl?FID=".$playfileid,
				'Referer' => "http://".$this->host.""
			]);
			return $arr;
		} else {
			$arr = $this -> http -> get([
				'User-Action' => "web://".$this->AppID.$this->http->GetUriUser()."@".$this->host."/Resource/GetOnlinePlayUrl?FID=".$playfileid."&host=".$hosturl,
				'Referer' => "http://".$this->host.""
			]);
			return $arr;
		}
		
	}

	/**
	 * 获取资源Info
	 *
	 * @param fileid :该资源的在平台中的唯一ID号，为32位字符串。该值为服务器所生成。
	 * @return
	 * [
	 *     'message' => string 'OK'       //成功返回ok.错误返回error
	 *     "data": {}
	 *     'code' => int 200     //通信成功200
	 * ]
	 */
	public function getFileInfo($pinurl)
	{
		$urls = $this -> url.$pinurl;
		$this -> http = Http::create() -> init($urls);
		$arr =  $this -> http -> get([
			'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@". $this -> host ."/Resource/GetFileInfo",
			'Referer' => 'http://'. $this -> host .''
		]);
		return $arr;
	}
	
}