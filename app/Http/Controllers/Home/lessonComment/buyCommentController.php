<?php

namespace App\Http\Controllers\Home\lessonComment;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Log;
use Input;
use PaasResource;
use PaasUser;
use Cache;
use QrCode;
use Primecloud\Pay\Weixin\Kernel\WxPayConfig;
use Primecloud\Pay\Weixin\Kernel\WxPayApi;
use Primecloud\Pay\Weixin\Kernel\WxPayDataBase;
use Primecloud\Pay\Weixin\Kernel\WxPayUnifiedOrder;
use Primecloud\Pay\Weixin\Kernel\WxPayResults;

class buyCommentController extends Controller
{
    use Gadget;


    public function __construct()
    {
        PaasUser::apply();
    }


    /**
     * 支付页面
     *
     * @return \Illuminate\Http\Response
     */
    public function index($teacherID)
    {
        $result = DB::table('teacher')->select('stock')->where(['parentId' => $teacherID])->where('stock', '>', 0)->first();
        $result || abort(404);
        return view('home.lessonComment.buyComment.index')->with('teacherID', $teacherID)->with('stock', $result->stock);
    }


    /**
     * 微信扫码
     *
     * @return \Illuminate\Http\Response
     */
    public function scan(WxPayApi $wxPay, WxPayDataBase $wxBase, WxPayUnifiedOrder $inputObj, $orderID)
    {
        $result = DB::table('orders')->select('orderPrice', 'orderTitle', 'orderSn', 'id', 'orderType')->where(['id' => $orderID, 'userId' => \Auth::user()->id, 'isDelete' => 0, 'status' => 5])->first();
        $result || abort(404);
		try {
			$code_url = $this->makeUnifiedOrder($wxPay, $inputObj, $wxBase, $result, 'http://artopcq.zuren8.com/lessonComment/wxPayCallback');
			empty($code_url['code_url']) && abort(404);
			return view('home.lessonComment.buyComment.scan')->with('orderID', $orderID)->with('orderInfo', $result)->with('url', $code_url['code_url']);
		} catch (\Exception $e) {
            DB::table('orders')->where('id', $orderID)->delete();
            return redirect()->back();
		}
    }


    /**
     * 支付成功
     *
     * @return \Illuminate\Http\Response
     */
    public function buySuccess($orderID)
    {
        $result = DB::table('orders')->where(['id' => $orderID, 'userId' => \Auth::user()->id, 'status' => 0, 'isDelete' => 0])->first();
        $result || abort(404);
        return view('home.lessonComment.buyComment.buySuccess')->with('orderID', $orderID);
    }


    /**
     * 上传视频
     *
     * @return \Illuminate\Http\Response
     */
    public function upload($orderID)
    {
        PaasUser::apply();
        $result = DB::table('orders')->select('id', 'orderSn', 'teacherId')->where(['id' => $orderID, 'userId' => \Auth::user()->id, 'status' => 0])->first();
        $result || abort(404);
        return view('home.lessonComment.buyComment.upload')->with('info', $result)->with('mineID', \Auth::user()->id);
    }


    /**
     * 审核未通过重新上传视频
     *
     * @return \Illuminate\Http\Response
     */
    public function reUpload($applyID, $messageID = null)
    {
        PaasUser::apply();
        $result = DB::table('applycourse')->select('id', 'courseTitle', 'message')->where(['id' => $applyID, 'userId' => \Auth::user()->id, 'state' => 0, 'courseStatus' => 0, 'courseIsDel' => 0])->first();
        $result || abort(404);
        $messageID || $messageID = 0;
        return view('home.lessonComment.buyComment.reUpload')->with('applyID', $result->id)->with('courseTitle', $result->courseTitle)
              ->with('message', $result->message)->with('mineID', \Auth::user()->id)->with('messageID', $messageID);
    }


    /**
     * 生成订单
     *
     * @return \Illuminate\Http\Response
     */
    public function generateOrder(Request $request)
    {
        $flag = DB::table('orders')->select('id')->where(['userId' => $request['userId'], 'courseId' => $request['courseId'], 'status' => 5, 'orderType' => $request['orderType']])->first();
        if(!$flag){
            $request['orderSn'] = date('Ymd', time()) . uniqid();
            $request['created_at'] = Carbon::now();
            $request['updated_at'] = Carbon::now();
            $result = DB::table('orders')->insertGetId($request->all());
        }else{
            $result = $flag->id;
        }
        return $this->returnResult($result);
    }


    /**
     * 完成上传
     *
     * @return \Illuminate\Http\Response
     */
    public function finishUpload(Request $request)
    {
        foreach ($request['data'] as $key => $value) {
			$request['data'][$key] && $data[$key] = $request['data'][$key];
		}
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        $data['state'] = 1;
        $data['courseTitle'] = str_replace(' ', '', $data['courseTitle']);
		isset($data['message']) ? $data['message'] = str_replace(' ', '', $data['message']) : $data['message'] = '';
        $result = DB::table('applycourse')->insertGetId($data);
        if (!$result) {
            return $this->returnResult(false);
        }
        DB::table('orders')->where('id', $request['orderID'])->update(['status' => 1]) || $result = !(DB::table('applycourse')->where('id', $result)->delete());
        return $this->returnResult($result);
    }


    /**
     * 微信支付回调地址
     *
     * @return \Illuminate\Http\Response
     */
    public function wxPayCallback(WxPayDataBase $wxBase)
    {
        try {
            $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
            $xml = WxPayResults::Init($xml);
            if ($xml) {
                if ($xml['result_code'] == 'SUCCESS' && $xml['return_code'] == 'SUCCESS') {
                    $orderSn = $xml['out_trade_no'];
                    $result['payPrice'] = $xml['total_fee'];
                    $result['tradeSn'] = $xml['transaction_id'];
                    $result['payTime'] = Carbon::now();

                    $order = DB::table('orders')->select('orderType')->where('orderSn', $orderSn)->first();
                    switch ($order->orderType) {
                        case 1:
                            $result['status'] = 0;
                            DB::table('teacher')->join('orders', 'teacher.parentId', '=', 'orders.teacherId')->where(['orders.orderSn' => $orderSn])->decrement('teacher.stock');
                            break;
                        case 2:
                            $result['status'] = 2;
                            DB::table('commentcourse')->join('orders', 'commentcourse.id', '=', 'orders.courseId')->where('orders.orderSn', $orderSn)->increment('commentcourse.coursePlayView');
							DB::table('commentcourse')->join('orders', 'commentcourse.id', '=', 'orders.courseId')->where('orders.orderSn', $orderSn)->increment('commentcourse.courseStudyNum');
                            break;
                    }

                    $order = DB::table('orders')->where('orderSn', $orderSn)->update($result);
                    if ($order) {
                        echo "SUCCESS";
                    }
                } else {
                    Log::debug(json_encode($xml) . " --- wxPayCallback 订单支付未成功");
                }
            } else {
                Log::debug(json_encode($xml) . " --- wxPayCallback 订单校验失败");
            }
        } catch (\Exception $e) {
            Log::debug($e->getMessage() . " --- wxPayCallback 抛出异常");
            Log::debug(json_encode($xml) . " --- wxPayCallback 异常数据");
        }
    }


    /**
     * 微信扫码获取订单状态
     *
     * @return \Illuminate\Http\Response
     */
    public function orderStatus($orderID)
    {
        $result = DB::table('orders')->select('id', 'orderType', 'courseId')->where('status', '!=', 5)->where(['id' => $orderID, 'userId' => \Auth::user()->id, 'isDelete' => 0])->first();
        return $this->returnResult($result);
    }


    /**
     * 支付宝异步回调页面
     *
     * @return \Illuminate\Http\Response
     */
    public function alipayAsyncCallback()
    {
        if (!app('alipay.web')->verify()) {
            Log::debug('支付宝异步校验失败 ', [
                'data' => json_encode(Input::all())
            ]);
            return 'fail';
        }

        if (Input::get('trade_status') == 'TRADE_SUCCESS' || Input::get('trade_status') == 'TRADE_FINISHED') {
            return 'success';
        }
    }


    /**
     * 支付宝同步回调页面
     *
     * @return \Illuminate\Http\Response
     */
    public function alipaySyncCallback()
    {
        if (!app('alipay.web')->verify()) {
            Log::debug('支付宝同步校验失败 ', [
                'data' => json_encode(Input::all())
            ]);
            abort(404);
        }

        if (Input::get('trade_status') == 'TRADE_SUCCESS' || Input::get('trade_status') == 'TRADE_FINISHED') {
            $orderSn = Input::get('out_trade_no');
            $result['payPrice'] = Input::get('total_fee') * 100;
            $result['tradeSn'] = Input::get('trade_no');
            $result['payTime'] = Carbon::now();

            if (preg_match('/^\/lessonComment\/buySuccess\/[0-9]{1,}/', Input::get('body'))) {
                $result['status'] = 0;
                DB::table('teacher')->join('orders', 'teacher.parentId', '=', 'orders.teacherId')->where('orders.orderSn', $orderSn)->decrement('teacher.stock');
            } else if (preg_match('/^\/lessonComment\/detail\/[0-9]{1,}/', Input::get('body'))) {
                $result['status'] = 2;
                DB::table('commentcourse')->join('orders', 'commentcourse.id', '=', 'orders.courseId')->where('orders.orderSn', $orderSn)->increment('commentcourse.coursePlayView');
                DB::table('commentcourse')->join('orders', 'commentcourse.id', '=', 'orders.courseId')->where('orders.orderSn', $orderSn)->increment('commentcourse.courseStudyNum');
            }
            $order = DB::table('orders')->where('orderSn', $orderSn)->update($result);
            if ($order) {
                return redirect()->to(Input::get('body'));
            } else {
                abort(404);
            }
        }
    }


    /**
     * 支付宝支付
     *
     * @return \Illuminate\Http\Response
     */
    public function alipay($orderID, $callback)
    {
        $result = DB::table('orders')->select('orderPrice', 'orderTitle', 'orderSn', 'id')->where(['id' => $orderID, 'userId' => \Auth::user()->id, 'isDelete' => 0, 'status' => 5])->first();
        $result || abort(404);
        $callback = '/' . str_replace('&', '/', $callback);
        $alipay = app('alipay.web');
        $alipay->setOutTradeNo($result->orderSn);
        $alipay->setTotalFee($result->orderPrice / 100);
        $alipay->setSubject($result->orderTitle);
        $alipay->setBody($callback);
        $alipay->setItBPay('10m');
        $alipay->setQrPayMode('2');
        return redirect()->to($alipay->getPayLink());
    }
}
