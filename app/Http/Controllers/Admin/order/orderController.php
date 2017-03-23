<?php

namespace App\Http\Controllers\Admin\order;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use QrCode;
use Primecloud\Pay\Weixin\Kernel\WxPayConfig;
use Primecloud\Pay\Weixin\Kernel\WxPayApi;
use Primecloud\Pay\Weixin\Kernel\WxPayDataBase;
use Primecloud\Pay\Weixin\Kernel\WxPayRefund;
use Log;
use Input;


class orderController extends Controller
{
    /**
     *订单列表
     */
    public function orderList(Request $request,$status){
//        dd($status);
        $query = DB::table('orders');
        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('created_at','<=',$request['endTime']);
        }
        if($request['type'] == 1){
            $query = $query->where('id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('orderSn','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('tradeSn','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 4){
            $query = $query->where('orderTitle','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 5){
            $query = $query->where('userId','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 6){
            $query = $query->where('userName','like','%'.trim($request['search']).'%');
        }


        //导出数据
        $query = $query
            ->select('id','orderSn as 订单号','tradeSn as 交易编号','orderTitle as 订单名称','orderPrice as 订单价格','payPrice as 实付金额','payType as 支付方式(0:支付宝1:微信2:其它)','userId as 购买用户ID','userName as 购买用户','teacherId as 邀请人ID','teacherName as 邀请人','orderType as 订单类型(0:购买专题订单1:点评申请订单2:购买点评订单)','courseId as 专题课程ID(订单类型为0或1时为点评课程ID)','refundableAmount as 应退金额','refundAmount as 已退金额','payTime as 付款时间','status as 订单状态(0:已付款1:待点评2:已完成3:退款中4:已退款5:未付款)')
            ->where('isDelete',0);
        if($status == 8){
            $query = $query;
        }else{
            $query = $query->where('status',$status);
        }
        $excel = $query
            ->orderBy('id','desc')
            ->get();
        foreach($excel as &$value){
            $value->订单价格 = $value->订单价格 / 100;
            $value->实付金额 = $value->实付金额 / 100;
            $value->应退金额 = $value->应退金额 / 100;
            $value->已退金额 = $value->已退金额 / 100;

            //取出备注
            $remarks = DB::table('remarks')->where('orderid',$value->id)->lists('content');
            $remarks = implode('/',$remarks);
            $value->备注 = $remarks;//导出备注
        }
//        dd($excel);
        $excel = json_encode($excel);

        $query = $query->where('isDelete',0);
        if($status == 8){ //全部订单
            $query = $query;
        }else{
            $query = $query->where('status',$status);
        }
        $data = $query
            ->orderBy('id','desc')
            ->select('*')
            ->paginate(10);

        foreach($data as &$val){
            $val->orderPrice = $val->orderPrice / 100;
            $val->payPrice = $val->payPrice / 100;
            $val->refundableAmount = $val->refundableAmount / 100;
            $val->refundAmount = $val->refundAmount / 100;
        }

        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
        $data->status = $status;
        return view('admin.order.orderList',['data'=>$data,'excel'=>$excel]);
    }

    /**
     *订单状态
     */
    public function orderState(Request $request){
        $data['status'] = $request['status'];
        $data['updated_at'] = Carbon::now();
        $data = DB::table('orders')->where('id',$request['id'])->update($data);
        $this -> OperationLog('修改了id为'.$request['id'].'的订单状态');
        switch($request['status']){
            case 0: //已付款
                DB::table('applycourse')->where('orderSn',$request['orderSn'])->update(['state'=>1]);
                DB::table('commentcourse')->where('orderSn',$request['orderSn'])->update(['state'=>1]);
                break;
            case 1: //待点评
                DB::table('applycourse')->where('orderSn',$request['orderSn'])->update(['state'=>2]);
                DB::table('commentcourse')->where('orderSn',$request['orderSn'])->update(['state'=>1]);
                break;
            case 2: //已完成
                DB::table('applycourse')->where('orderSn',$request['orderSn'])->update(['state'=>2]);
                DB::table('commentcourse')->where('orderSn',$request['orderSn'])->update(['state'=>2]);
                break;
        }
        if($data){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     *删除订单
     */
    public function delOrder($id,$status){
        $orderSn = DB::table('orders')->where('id',$id)->pluck('orderSn');
        $status = DB::table('orders')->where('id',$id)->pluck('status');
        if($status != 4){ //只有订单是已退款的才可以删除订单
            return redirect()->back()->withInput()->withErrors('只有已退款的订单才可以删除');
        }
        $data = DB::table('orders')->where('id',$id)->update(['isDelete'=>1]);
        DB::table('applycourse')->where('orderSn',$orderSn)->update(['courseIsDel'=>1]); //已退款时关联删除演奏视频表
        DB::table('commentcourse')->where('orderSn',$orderSn)->update(['courseIsDel'=>1]); //已退款时关联删除名师点评表
        if($data){
            $this -> OperationLog('删除了id为'.$id.'的订单');
            return redirect('admin/message')->with(['status'=>'订单删除成功','redirect'=>'order/orderList/'.$status]);
        }else{
            return redirect('admin/message')->with(['status'=>'订单删除失败','redirect'=>'order/orderList/'.$status]);
        }
    }

    /**
     *清除垃圾订单
     */
    public function deleteOrders(){
        $data = DB::table('orders')->where(['status'=>5,'isDelete'=>0])->get();
        $count = 0;
        foreach($data as &$val){
            if($val->created_at < Carbon::now()->subDay(1)){ //如果未付款订单超过一天则删除 subDay(1) 一天前
                $da = DB::table('orders')->where('id',$val->id)->delete();
                $count += $da;
            }
        }
        if($count){
            $this -> OperationLog('共清除了'.$count.'条垃圾订单');
        }
        return redirect()->back()->withInput()->with(['status'=>'垃圾订单已清除']);
    }




    /**
     *订单备注
     */
    public function remark(Request $request){
        $data = $request->all();
        $data['created_at'] = Carbon::now();
        if($id = DB::table('remarks')->insertGetId($data)){
            $this -> OperationLog('添加了id为'.$id.'的订单备注');
            $state['state'] = 1;
        }else{
            $state['state'] = 0;
        }
        echo json_encode($state);
    }

    /**
     *修改应退金额
     */
    public function editRefundmoney($id,$status){
        $data = DB::table('orders')->where('id',$id)->select('id','orderSn','refundableAmount')->first();
        $data->refundableAmount = $data->refundableAmount / 100;
        $data->status = $status;
        return view('admin.order.editRefundmoney',['data'=>$data]);
    }

    /**
     *执行修改应退金额
     */
    public function doRefundmoney(Request $request){
//        $validator = $this -> validator($request->all());
//        if ($validator -> fails()){
//            return Redirect()->back()->withInput()->withErrors($validator);
//        }
        $payPrice = DB::table('orders')->where('id',$request['id'])->pluck('payPrice'); //实付金额
        $payPrice = $payPrice / 100;
        if($request['refundableAmount'] > $payPrice){
            return Redirect()->back()->withInput()->withErrors('应退金额不能大于实付金额');
        }
        $data = $request->except('_token','status');
        $data['updated_at'] = Carbon::now();
        $data['refundableAmount'] = $request['refundableAmount'] * 100;
        if(DB::table('orders')->where('id',$request['id'])->update($data)){
            $this -> OperationLog('修改了id为'.$request['id'].'的订单应退金额');
            return redirect('admin/message')->with(['status'=>'成功','redirect'=>'order/orderList/'.$request['status']]);
        }else{
            return redirect('admin/message')->with(['status'=>'失败','redirect'=>'order/orderList/'.$request['status']]);
        }
    }

    /**
     *修改已退金额
     */
    public function editRetiredmoney($id,$status){
        $data = DB::table('orders')->where('id',$id)->select('id','orderSn','refundAmount')->first();
        $data->refundAmount = $data->refundAmount / 100;
        $data->status = $status;
        return view('admin.order.editRetiredmoney',['data'=>$data]);
    }

    /**
     *执行修改已退金额
     */
    public function doRetiredmoney(Request $request){
//       $this->validate($request,[
//           'refundAmount'=>'integer'
//       ],[
//           'refundAmount.integer'=>'已退金额必须为整型'
//       ]);
        $refundableAmount = DB::table('orders')->where('id',$request['id'])->pluck('refundableAmount'); //应退金额
        $refundableAmount = $refundableAmount / 100;
        if($request['refundAmount'] > $refundableAmount){
            return Redirect()->back()->withInput()->withErrors('已退金额不能大于应退金额');
        }
        $data = $request->except('_token','status');
        $data['refundAmount'] = $request['refundAmount'] * 100;
        $data['updated_at'] = Carbon::now();
        if(DB::table('orders')->where('id',$request['id'])->update($data)){
            $this -> OperationLog('修改了id为'.$request['id'].'的订单已退金额');
            return redirect('admin/message')->with(['status'=>'成功','redirect'=>'order/orderList/'.$request['status']]);
        }else{
            return redirect('admin/message')->with(['status'=>'失败','redirect'=>'order/orderList/'.$request['status']]);
        }
    }

    /**
     *修改实付金额
     */
    public function editPaymoney($id,$status){
        $data = DB::table('orders')->where('id',$id)->select('id','orderSn','payPrice','payType')->first();
        $data->payPrice = $data->payPrice / 100;
        $data->status = $status;
        return view('admin.order.editPaymoney',['data'=>$data]);
    }
    /**
     *执行修改实付金额
     */
    public function doEditPaymoney(Request $request){
//       $this->validate($request,[
//           'payPrice'=>'integer'
//       ],[
//           'payPrice.integer'=>'实付金额必须为整型'
//       ]);
        $orderPrice = DB::table('orders')->where('id',$request['id'])->pluck('orderPrice'); //订单价格
        $orderPrice = $orderPrice / 100;
        if($request['payPrice'] > $orderPrice){
            return Redirect()->back()->withInput()->withErrors('实付金额不能大于订单价格');
        }
        $data = $request->except('_token','status');
        $data['payPrice'] = $request['payPrice'] * 100;
        $data['payType'] = $request['payType'];
        $data['payTime'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        if(DB::table('orders')->where('id',$request['id'])->update($data)){
            $this -> OperationLog('修改了id为'.$request['id'].'的订单实付金额');
            return redirect('admin/message')->with(['status'=>'成功','redirect'=>'order/orderList/'.$request['status']]);
        }else{
            return redirect('admin/message')->with(['status'=>'失败','redirect'=>'order/orderList/'.$request['status']]);
        }
    }


    /**
     *备注列表
     */
    public function remarkList($id,$staus){
        $data = DB::table('remarks as r')
            ->leftJoin('orders as o','r.orderid','=','o.id')
            ->select('r.*','o.id as orderid','o.orderSn')
            ->where('orderid',$id)
            ->paginate(15);
        $data->status = $staus;
        return view('admin.order.remarkList',['data'=>$data]);
    }

    /**
     *删除备注
     */
    public function delRemark($orderid,$id,$status){
        if(DB::table('remarks')->where('id',$id)->delete()){
            $this -> OperationLog('删除了id为'.$id.'的订单备注');
            return redirect('admin/message')->with(['status'=>'备注删除成功','redirect'=>'order/remarkList/'.$orderid.'/'.$status]);
        }else{
            return redirect('admin/message')->with(['status'=>'备注删除失败','redirect'=>'order/remarkList/'.$orderid.'/'.$status]);
        }
    }










    //==================================================
    //================退款列表==================================
    //==================================================
    /**
     * 退款列表
     */
    public function refundList(Request $request,$orderSn){
        $query = DB::table('refund');
        if($request['type'] == 1){
            $query = $query->where('orderSn','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('orderTitle','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('username','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 4){ //上传的起止时间
            $query = $query->where('created_at','>=',$request['beginTime'])->where('created_at','<=',$request['endTime']);
        }
        $data = $query
            ->where('orderSn',$orderSn)
            ->paginate(15);
        $data->type = $request['type'];
        return view('admin.order.refundList',['data'=>$data]);
    }

    /**
     *退款状态
     */
    public function refundState(Request $request){
        $data['status'] = $request['status'];
        $data['updated_at'] = Carbon::now();
        $data = DB::table('refund')->where('id',$request['id'])->update($data);
        if($data){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     *删除退款
     */
    public function delRefund($orderSn,$id){
        $data = DB::table('refund')->where('id',$id)->delete();
        if($data){
            $this -> OperationLog('删除了id为'.$id.'的订单退款信息');
            return redirect('admin/message')->with(['status'=>'退款删除成功','redirect'=>'order/refundList/'.$orderSn]);
        }else{
            return redirect('admin/message')->with(['status'=>'退款删除失败','redirect'=>'order/refundList/'.$orderSn]);
        }
    }

    /**
     * 批量删除已退订单
     */
    public function deletes(Request $request){
        $ids = $request['id'];
        $count = 0;
        foreach($ids as $id){
            $count = DB::table('orders')->where('id',$id)->update(['isDelete'=>1]);
            $count += $count ;
            DB::table('remarks')->where('orderid',$id)->delete();//删除订单备注表

            $orderSn = DB::table('orders')->where('id',$id)->pluck('orderSn');
            DB::table('applycourse')->where('orderSn',$orderSn)->update(['courseIsDel'=>1]); //同时删除演奏视频
            DB::table('commentcourse')->where('orderSn',$orderSn)->update(['courseIsDel'=>1]);//同时删除点评视频
        }
        $this -> OperationLog('批量删除了'.$count.'条订单');
        if($count){
            echo '1';
        }else{
            echo '0';
        }

    }


    /**
     * 表单验证
     */
    protected function validator(array $data){
        $rules = [
            'refundableAmount' => 'required|integer',

        ];

        $messages = [
            'refundableAmount.required' => '请输入应退金额',
            'refundableAmount.integer' => '应退金额必须为整型'
        ];

        return \Validator::make($data, $rules, $messages);
    }


    /**
     *点击确认退款
     */
    public function weiXinRefund(WxPayApi $wxPay,WxPayDataBase $wxPayDataBase,WxPayRefund $wxPayRefund,$orderid){
        $data = DB::table('orders')->where(['id'=>$orderid,'isDelete'=>0])->first();
        if(!$data->refundAmount){
            return Redirect()->back()->withInput()->withErrors('请填写已退金额');
        }
        $totalPrice = $data->orderPrice;
        $refundAmount = $data->refundAmount;
        $orderSn = $data->orderSn;//取出订单号
        $tradeSn = $data->tradeSn;//取出微信订单号

        //设置微信订单号
        $wxPayRefund->SetTransaction_id($tradeSn);
        //设置退款单号
        $wxPayRefund->SetOut_refund_no($orderSn);
        //设置订单总金额
        $wxPayRefund->SetTotal_fee($totalPrice);
        //设置退款总金额
        $wxPayRefund->SetRefund_fee($refundAmount);
        //设置商户号
        $wxPayRefund->SetOp_user_id(WxPayConfig::MCHID);


        $result = $wxPay->refund($wxPayRefund);
        if($result['return_code'] == 'SUCCESS'){
            DB::table('orders')->where('id',$orderid)->update(['status'=>4]);//如果退款成功将订单状态改为4已退款
            $this -> OperationLog('id为'.$orderid.'的订单确认了退款');
            return redirect('admin/message')->with(['status'=>'退款成功','redirect'=>'order/orderList/4']);
        }else{
            return redirect('admin/message')->with(['status'=>'退款失败','redirect'=>'order/orderList/3']);
        }
    }

    /**
     *支付宝确认退款
     */
    public function alipayRefund($orderId){
        $order = DB::table('orders')->where('id',$orderId)->where('isDelete',0)->first();
        if(!$order->refundAmount){
            return Redirect()->back()->withInput()->withErrors('请填写已退金额');
        }
        $alipay = app('alipay.web');
        $alipay->setService('refund_fastpay_by_platform_pwd');
        $alipay->setOutTradeNo($order->orderSn);//商户订单号
        $alipay->setTotalFee($order->orderPrice / 100);
        $alipay->setSubject($order->orderTitle);
        $alipay->setRefundDate(date('Y-m-d H:i:s'));//退款请求时间
        $alipay->setBatchNo(date('Ymd').time());  //退款批次号
        $alipay->setBatchNum(1);//总笔数
        $aa = $order->tradeSn.'^'.($order->refundAmount / 100).'^'.'正常退款'; //交易号^退款金额^退款理由
        $alipay->setDetailData($aa);//单笔数据集
//        $alipay->setReturnUrl('http://qinsheng.zuren8.com/admin/order/alipaySyncCallback');//同步回调
        $alipay->setNotifyUrl('http://qinsheng.zuren8.com/admin/order/alipayAsyncCallback');//异步回调
        $alipay->getPayLink();

        return redirect()->to($alipay->getPayLink());
    }

    /**
     * 支付宝异步回调页面
     *
     * @return \Illuminate\Http\Response
     */
    public function alipayAsyncCallback()
    {
        $this -> OperationOrderLog('订单异步日志'.json_encode(Input::all()));

        if (!app('alipay.web')->verify()) {
            Log::info('支付宝异步校验失败 ', [
                'data' => json_encode(Input::all())
            ]);
            return 'fail';
        }

        $resultDetails = explode('^',Input::get('result_details'));//交易号^退款金额^结果
        if ($resultDetails[2] == 'SUCCESS') {
            $tradeSn = $resultDetails[0]; //取出交易号
            DB::table('orders')->where('tradeSn', $tradeSn)->update(['status'=>4]);//订单状态改为已退款
            $this -> OperationOrderLog('订单交易号为'.$tradeSn.'的订单已退款');
            return 'success';
        }
    }


//    /**
//     * 支付宝同步回调页面
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function alipaySyncCallback()
//    {
//        $this -> OperationLog('同步日志为aaaaaa'.json_encode(Input::all()));
//        if (!app('alipay.web')->verify()) {
//            Log::info('支付宝同步校验失败 ', [
//                'data' =>json_encode(Input::all())
//            ]);
//            abort(404);
//        }
//
//        $resultDetails = explode('^',Input::get('result_details'));//交易号^退款金额^结果
//        if ($resultDetails[2] == 'SUCCESS') {
//            $tradeSn = $resultDetails[0]; //取出交易号
//            DB::table('orders')->where('tradeSn', $tradeSn)->update(['status'=>4]);
//            return redirect()->to('/admin/order/orderList/3');
//        }
//
//    }


//    public function alipayRefund($orderId){
////        dump($orderId);
//        $order = DB::table('orders')->where('id',$orderId)->where('isDelete',0)->first();
//
//        $alipay = app('alipay.web');
//        $alipay->setService('alipay.trade.refund');
//        $alipay->setOutTradeNo($order->orderSn);//商户订单号
////        $alipay->setTotalFee($order->orderPrice / 100);
//        $alipay->setRefund($order->refundAmount / 100); //需要退款的金额
////        $alipay->setSubject($order->orderTitle);
//        $aa = $alipay->getPayLink();
////        dd($aa);
//        return redirect()->to($alipay->getPayLink());
//    }

}






