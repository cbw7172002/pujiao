<?php

namespace App\Http\Controllers\Home\lessonComment;

use Illuminate\Http\Request;
use PaasResource;
use PaasUser;
use Primecloud\Pay\Weixin\Kernel\WxPayConfig;
use Log;
use Exception;

trait Gadget {

	public $number = 6;


    public function getSkip($page, $number) 
    {
        return ($page - 1) * $number;
    }


    public function returnResult($result)
    {
        if ($result) {
            return Response() -> json(["type" => true, "data" => $result]);
        } else {
            return Response() -> json(["type" => false]); 
        }
    }


    /**
     * pass平台资源临时播放url
     *
     * @param   fileID  该资源的在平台中的唯一ID号
     * @param   host    白名单域名   【可选】
     *
     * @return  \Illuminate\Http\Response
     */
    private function getPlayUrl($fileID, $host = '')
    {
		try {
			$path = PaasResource::clickplay($fileID, $host);
			if ($path['message'] == 'OK' && $path['code'] == 200) {
				return $path['data'];
			} else {
				return false;
			}
		} catch (Exception $e) {
			Log::debug($e -> getMessage().' --- getPlayUrl 抛出异常');
			return false;
		}
    }


    /**
     * pass平台上传资源
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadResource(Request $request)
    {
		try {
			if (!PaasUser::apply()) return Response() -> json(["type" => false, 'status' => '401']);
			$recourse = PaasResource::getuploadstatus("/?md5=". $request['md5'] ."&filename=". $request['filename'] ."&directory=". $request['directory']);
			return $this -> returnResult($recourse);
		} catch (Exception $e) {
			Log::debug($e -> getMessage().' --- uploadResource 抛出异常');
			return false;
		}
    }

    /**
     *pass平台资源下载
     */
    public function getdownload($fileID){
        try {
            if (!PaasUser::apply()) return Response() -> json(["type" => false, 'status' => '407']);
            $recourse = PaasResource::getdownload($fileID);
            return $recourse;
        } catch (Exception $e) {
            Log::debug($e -> getMessage().' --- transformations 抛出异常');
            return false;
        }
    }

    /**
     * pass平台资源转换
     *
     * @return \Illuminate\Http\Response
     */
    public function transformation(Request $request)
    {
		try {
			if (!PaasUser::apply()) return Response() -> json(["type" => false, 'status' => '401']);
			$recourse = PaasResource::transformation("?fileid=". $request['fileID']."&ConverType=".$request['convertype']);
			return $this -> returnResult($recourse);
		} catch (Exception $e) {
			Log::debug($e -> getMessage().' --- transformation 抛出异常');
			return false;
		}
    }

    /**
     * pass平台资源转换
     *
     * @return \Illuminate\Http\Response
     */
    public function transformations($fileID,$convertype)
    {
		try {
			if (!PaasUser::apply()) return Response() -> json(["type" => false, 'status' => '401']);
            $recourse = PaasResource::transformation("?fileid=". $fileID."&ConverType=".$convertype);
            return $recourse;
		} catch (Exception $e) {
			Log::debug($e -> getMessage().' --- transformations 抛出异常');
			return false;
		}
    }


    /**
     * 生成微信统一订单
     *
     * @return \Illuminate\Http\Response
     */
    public function makeUnifiedOrder($wxPay, $inputObj, $wxBase, $order, $callback)
    {
        $inputObj -> SetAppid(WxPayConfig::APPID);
        $inputObj -> SetMch_id(WxPayConfig::MCHID);
        $inputObj -> SetDevice_info("WEB");
        $inputObj -> SetNonce_str($wxPay -> getNonceStr());
        $inputObj -> SetSign();
        $inputObj -> SetBody($order -> orderTitle);
        $inputObj -> SetOut_trade_no($order -> orderSn);
        $inputObj -> SetTotal_fee(intval($order -> orderPrice));
        $inputObj -> SetSpbill_create_ip($_SERVER['REMOTE_ADDR']);
        $inputObj -> SetTime_expire(date('YmdHis', time() + 60 * 10));
        $inputObj -> SetNotify_url($callback);
        $inputObj -> SetProduct_id($order -> orderType);
        $inputObj -> SetTrade_type('NATIVE');
        return $wxPay -> unifiedOrder($inputObj);
    }

}