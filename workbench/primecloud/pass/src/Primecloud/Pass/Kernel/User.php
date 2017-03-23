<?php

namespace Primecloud\Pass\Kernel;

use Primecloud\Pass\Kernel\PassBase as Http;
use Primecloud\Pass\Kernel\Config;
use Illuminate\Http\Response;

class User
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
		$this -> http = Http::create() -> init($this -> url);
    }

	/**
     * 申请许可接口
     *
     * @param 参数无
     * @return 
     * [
     *  "data": [                                                
     *         "License": "e8c5c530c39c424f8a8e38704afaa7be"    // License:返回32位字符串表示通信成功,通信失败lincense为空。（表示通信许可ID）
     *          ],
     *  "message": "ok",									    //成功返回ok.错误返回error
     *  "code": 200											    //通信成功，通信失败返回401
     * ]
     */

	public function apply()
	{
		try {
			$array = $this -> http -> get([
				'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@". $this -> host ."/user/UserApplyLicense",
				'Referer' => "http://". $this -> host .""
			]);
			if ($array['code'] == '407') {
				return false;
			} else {
//				(!isset($_COOKIE['sessionid']) || $_COOKIE['sessionid'] != $array['data']['License']) && setcookie("sessionid", $array['data']['License'], time() + 60 * 60 * 24, '/');
				(!isset($_COOKIE['sessionid']) || $_COOKIE['sessionid'] != $array['data']['License']) && \Cookie::queue('sessionid', $array['data']['License'], 60 * 24);
				return $array;
			}
		} catch (\Exception $e) {
			throw new \Exception($e -> getMessage());
		}
	}


    /**
     * 用户登录接口
     *
     * @param 参数：username用户名、userpassword密码
     * @return 
     * [
     *  "data": [                                                
     *         "msg": "登录成功",								  //登录结果
     *         "ExecuteCode": 1     							 //通信结果(1成功，0失败)
     *          ],
     *  "message": "ok",									    //成功返回ok.错误返回error
     *  "code": 200											   //通信成功，通信失败返回401
     * ]
     */
    public function login($userName, $userPassword)
    {
		return $info = $this -> http -> post(
	        [
				'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@user/user/UserLogin",
				'Referer' => "http://". $this -> host .""
	        ],
	        [
	        	'userName' => $userName,
	        	'userPassword' => $userPassword
	        ]
        );
    }


	/**
	 *获取登录状态信息
     *
     * @param 参数无
     * @return 
     * [
     *  "message": "ok",									            //成功返回ok.错误返回error
     *  "data": [                                                
     *         "userID": "e4f4f9b6788640a7835d39082852f953",            //当前登录用户id
     *         "phone": "15801012007",						            //当前用户手机号码
     *         "email": "wangxiaoertest7@163.com",                      //当前用户邮箱
     *         "userName": "wangxiaoertest7",                           //当前用户名
     *         "type": 5									            //用户类型:1,2,3
     *          ],
     *  "code": 200											            //通信成功，通信失败返回401
     * ]
     */
	public function loginstatus()
	{
		return $array = $this -> http -> get([
		    'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@user/user/UserwhetherLogin",
			'Referer' => "http://". $this -> host .""
		]);
	}


    /**
     * 用户注册接口
     *
     * @param 参数：username用户名、userpassword密码、email邮箱、phone电话号码
     * @return 
     * [
     *  "data": [                                                
     *         "msg": "注册成功",								  //注册结果
     *         "ExecuteCode": 1     							 //通信结果(1成功，0失败)
     *          ],
     *  "message": "ok",									    //成功返回ok.错误返回error
     *  "code": 200											   //通信成功，通信失败返回407
     * ]
     */
    public function register($userName, $userPassword, $email, $phone) 
    {
		return $info = $this -> http -> post(
			[	
				'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@user/user/UserRegister",
				'Referer' => "http://". $this -> host .""
			],
			[
				'userName' => $userName,
				'userPassword' => $userPassword,
				'email' => $email,
				'phone' => $phone
			]
		);
    }


	/**
     * 修改密码接口
     *
     * @param 参数：oldpassword旧密码、newpassword新密码
     * @return 
     * [
     *  "data": [                                                
     *         "msg": "修改成功",								  //修改结果
     *         "ExecuteCode": 1     							  //通信结果(1成功，0失败)
     *          ],
     *  "message": "ok",									      //成功返回ok.错误返回error
     *  "code": 200											      //通信成功，通信失败返回407
     * ]
     */
	public function repasswd($oldpwd, $newpwd)
	{
		return $info = $this -> http -> post(
			[
				'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@user/user/UserUpdatePassword",
				'Referer' => "http://". $this -> host .""
			],
			[
				'oldPassword' => $oldpwd,
				'newPassword' => $newpwd
			]
		);
        //注册以后的操作
	}


	/**
     * 用户注销接口
     *
     * @param 参数无
     * @return 
     * [
     *  "data": [                                                
     *         "msg": "注销成功",								 //注销结果
     *          ],
     *  "message": "ok",									     //成功返回ok.错误返回error
     *  "code": 200											     //通信成功，通信失败返回407
     * ]
     */
	 public function login_exists()
	 {
		return $arr = $this -> http -> get([
			'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@user/user/UserExit",
			'Referer' => "http://". $this -> host ."",
		]);
	 }


	 /**
     * 申请注册开发者接口
     *
     * @param 参数：DeveloperName：开发者姓名 、Profile：简介、	Email：邮箱 、 Phone：手机号  、PapersNum：证件号码 、
     * @param       Website：开发者网站 、Whether：是否同意注册协议 （YES、NO）、 PapersType: 证件类型（为1，表示为：1---代表身份证 ）
     * @return 
     * [
     *  "data": [                                                
     *         "msg": "申请开发者成功",								  //申请结果
     *         "ExecuteCode": 1     							      //通信结果(1：成功，0：失败)
     *          ],
     *  "message": "ok",									          //成功返回ok.错误返回error
     *  "code": 200											          //通信成功，通信失败返回407
     * ]
     */
	 public function applydeveloper($DeveloperName, $Profile, $Email, $Phone, $papersNum, $Website, $Whether, $PapersType)
	 {
		return $info = $this -> http -> post(
			[
				'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@user/user/DeveloperInfo",
				'Referer' => "http://". $this -> host .""
			],
			[
				'DeveloperName' => $DeveloperName,
				'Profile' => $Profile, 
				'Email' => $Email,
				'Phone' => $Phone,
				'PapersNum' => $papersNum,
				'Website' => $Website,
				'Whether' => $Whether,
				'PapersType' => $PapersType
			]
		);
	 }


	 /**
     * 申请appid
     *
     * @param 参数：APPNameAPP名字、PackageName包名、 type为：0x01:web应用  0x02:IOS应用  0x04:android应用  0x08:桌面应用 
     * @param       type为（十六进制，代表申请APPID应用包含的类型，例如，当前APPID为web应用和IOS应用，它的type为0x03。即3；此时的1代表0x01即web应用）
     * @return 
     * [
     *  "data": [                                                
     *         "msg": "申请开发者成功",								  //申请APPID结果,返回为当前申请的APPID
     *         "ExecuteCode": 1     							      //通信结果(1：成功，0：失败)
     *          ],
     *  "message": "ok",									          //成功返回ok.错误返回error
     *  "code": 200											          //通信成功，通信失败返回407
     * ]
     */
	 public function applyid($APPName, $PackageName, $type)
	 {
		 return $info = $this -> http -> post(
			[
				'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@userID/user/UserCreateAPPID",
				'Referer' => "http://". $this -> host .""
			],
	 		[
	 			'APPName' => $APPName, 
	 			'PackageName' => $PackageName, 
	 			'type' => $type
	 		]
		 );
	 }

	 
	 /**
     * 获取appid列表信息
     *
     * @param 参数：无
     * @return type为：0x01:web应用  0x02:IOS应用  0x04:android应用  0x08:桌面应用 
     * @return 
     * [
     * "message": "ok",                                               //成功返回ok.错误返回error
     * "data": [												      //当前用户申请的APP详细列表
     *      "count": 3,                                               //当前用户申请的APP个数
     *      "list": [
     *        [
     *          "userID": "ce4e8929666043538bec82ef8cb71076",		  //该申请者的userid
     *           "_id": [
     *                   "$oid": "5750027c07e99bf2d8313321"           //申请的userid
     *                  ],
     *                  "PackageName": "www.sun.com17",			      //包名
     *                  "APPID": "a95a745bbb9849419e6a5c92c4488ef4",  //app的id值
     *                  "type": 1,									  //type为十六进制，代表申请APPID应用包含的类型，例如，当前APPID为web应用和IOS应用，它的type为0x03。即3；此时的1代表0x01即web应用
     *                  "APPName": "启云web17"
     *       ],
     *    	 [
     *        "userID": "ce4e8929666043538bec82ef8cb71076",
     *        "_id":  [
     *                  "$oid": "575003a207e99bf2d8313322"
     *                ],
     *                 "PackageName": "www.sun.com18",
     *                 "APPID": "6067d6ddff544c5cbf53f3b344f264db",
     *                 "type": 1,
     *                 "APPName": "启云web18"
     *      ],
     *      [
     *        "userID": "ce4e8929666043538bec82ef8cb71076",
     *        "_id":  [
     *                "$oid": "575003d107e99bf2d8313323"
     *               ],
     *                "PackageName": "www.sun.com19",
     *                "APPID": "111930af6bfc421596512e6357103c14",
     *                "type": 1,
     *                "APPName": "启云web19"
     *     ],
     * ],
     * "ExecuteCode": 1                                            //通信结果(1：成功,0失败) 
     *  ],
     * "code": 200												   //通信成功
     *  ]
     */
	 public function getapplist()
	 {
		return $info = $this -> http -> get([
			'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@user/user/UserselectInfo?fristnum=0&addnum=10",
			'Referer' => "http://". $this -> host ."",
		]);
	 }


	/**
	* 修改appid接口
     *
     * @param 参数：APPNameAPP名字、PackageName包名、 type为：0x01:web应用  0x02:IOS应用  0x04:android应用  0x08:桌面应用 
     * @param       type为（十六进制，代表申请APPID应用包含的类型，例如，当前APPID为web应用和IOS应用，它的type为0x03。即3；此时的1代表0x01即web应用）
     * @return 
     * [
     *  "data": [                                                
     *         "msg": "修改成功",								      //修改结果
     *         "ExecuteCode": 1     							      //通信结果(1：成功，0：失败)
     *          ],
     *  "message": "ok",									          //成功返回ok.错误返回error
     *  "code": 200											          //通信成功，通信失败返回407
     * ]
     */
	public function updateappid($APPName, $PackageName, $type, $appid)
	{
		return $arr = $this -> http -> post(
			[
				'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@user/user/UserUpdateAPPID",
				'Referer' => "http://". $this -> host ."",
			],
			[
				'APPName' => $APPName, 
				'PackageName' => $PackageName, 
				'type' => $type, 
				'APPID' => $appid
			]
		);
	}


	/**
	* 删除appid接口
     *
     * @param 参数：appid: AppID
     * @return 
     * [
     *  "data": [                                                
     *         "msg": "删除成功",								      //删除结果
     *         "ExecuteCode": 1     							      //通信结果(1：成功，0：失败)
     *          ],
     *  "message": "ok",									          //成功返回ok.错误返回error
     *  "code": 200											          //通信成功，通信失败返回407
     * ]
     */
    public function deleteapplyid($appid)
    {
		return $info = $this -> http -> post(
			[
				'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@user/user/UserDelAppid",
				'Referer' => "http://". $this -> host .""
			],
			[
				'appid' => $appid
			]
		);	
	}

	
	/**
	* 获取角色列表接口
     * 
     * @param 参数：无
     * @return 
     * [
     *  	"data": [                                                
     *        "RoleList": [                           //角色列表
     *           "57441fae1fe55e165c1432f9",
     *            "权限管理",
     *            "57441fae1fe55e165c143303",
     *            "资源上传下载",
     *            "57441fae1fe55e165c143304",
     *            "资源转换",
     *            "57441fae1fe55e165c143317",
     *            "APP管理",
     *            "57441fae1fe55e165c143318",
     *            "用户管理"
     *   				],
     * 		"ExecuteCode": 1                                              //通信结果(1：成功，0：失败)
     * 				],
     *  	"message": "ok",									          //成功返回ok.错误返回error
     * 	    "code": 200											          //通信成功，通信失败返回407
     * ]
     */
	public function rolelist()
	{
		return	$this -> http -> get([				
			'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@user/rbac/GetRoleList",
			'Referer' => "http://". $this -> host .""
		]);
		
	}


	/**
	* 获取该用户的角色列表接口(根据appid查询)
     * 
     * @param 参数：appid:APPID
     * @return 
     * [
     *  	"data": [                                                
     *        "RoleList": [                           //角色列表
     *           "57441fae1fe55e165c1432f9",
     *            "权限管理",
     *            "57441fae1fe55e165c143303",
     *            "资源上传下载",
     *            "57441fae1fe55e165c143304",
     *            "资源转换",
     *            "57441fae1fe55e165c143317",
     *            "APP管理",
     *            "57441fae1fe55e165c143318",
     *            "用户管理"
     *   				],
     * 		"ExecuteCode": 1                                              //通信结果(1：成功，0：失败)
     * 				],
     *  	"message": "ok",									          //成功返回ok.错误返回error
     * 	    "code": 200											          //通信成功，通信失败返回407
     * ]
     */
	public function appidrolelist($appid)
	{
		return $this -> http -> get([
			'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@user/rbac/GetRoleList?APPID=". $appid,
			'Referer' => "http://". $this -> host .""
		]);
	}


	/**
	* 获取验证码
     * 
     * @param 参数：phone:手机号码
     * @return 
     * [
     *  	"data": [      
     *              "msg":"短信发送成功";										   //返回短信发送成功   验证码发送到手机上                                 
     * 		        "ExecuteCode": 1                                              //通信结果(1：成功，0：失败)
     * 				],
     *  	"message": "ok",									          //成功返回ok.错误返回error
     * 	    "code": 200											          //通信成功，通信失败返回407
     * ]
     */
	public function getcodes($phone)
	{
		return $info = $this -> http -> post(
			[
				'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@user/user/GetAuthCode",
				'Referer' => "http://". $this -> host .""
			],
			[
				'phone' => $phone
			]
		);	
		
	}


	/**
	* 忘记密码接口
     * 
     * @param 参数：username用户名，code接收的验证码，newpwd新密码
     * @return 
     * [
     *  	"data": [      
     *              "msg":"修改成功";										       //修改结果                        
     * 		        "ExecuteCode": 1                                              //通信结果(1：成功，0：失败)
     * 				],
     *  	"message": "ok",									          //成功返回ok.错误返回error
     * 	    "code": 200											          //通信成功，通信失败返回407
     * ]
     */
	public function forgetpwds($username, $code, $newpwd)
	{
		return $info = $this -> http -> post(
			[
				'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@user/user/ForgotPwd",
				'Referer' => "http://". $this -> host .""
			],
			[
				'userName' => $username,
				'authcode' => $code,
				'password' => $newpwd
			]
		);	
	}


	
	/**
	* APP对应的角色的接口(包括添加，删除)
     * 
     * @param 参数：pinurl拼接过来的url
     * @return 返回添加成功：true,返回添加失败返回false;返回删除成功：true,失败false
     */
	public function makerole($pinurl)
	{
		$arr = $this -> http -> get([
			'User-Action' => "web://".$this -> AppID.$this -> http -> GetUriUser() ."@user/rbac/APPIDRoleImp". $pinurl,
			'Referer' => "http://". $this -> host .""
		]);
		return $arr;
	}


    /*
     * 封装post方法
     */
    private function methodpost($geturl, $body) 
    {
        return $info = $this -> http -> post([
	        'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@$geturl",
	        'Referer' => "http://". $this -> host .""
        ],$body);
    }


	/*
	* 封装get方法
	*/
	private function methodget($geturl)
	{
		return $info = $this -> http -> get([
            'User-Action' => "web://". $this -> AppID.$this -> http -> GetUriUser() ."@$geturl",
            'Referer' => "http://". $this -> host .""
        ]);
	}

}