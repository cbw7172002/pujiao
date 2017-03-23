<?php

namespace App\Http\Controllers\Admin\count;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class userCountController extends Controller
{

    public function userCountList(){
        $data = [];
        $time = date('Y-m',time());
        for($i = 0;$i < 24;$i++){
            $data[] = date('Y-m',strtotime('-'.$i.' month '.$time));
        }
        return view('admin.count.usersCountList',['data'=>$data]);
    }

    /**
     *用户每天注册人数
     */
    public function userCount($time = null,$orders = 'users'){
        $data = [];
        if($time != "null"){
            $dates = explode('-',$time);
            $year = $dates[0];
            $month = $dates[1];
        }else{
            //获取当前时间
            $year = date('Y',time());
            $month = date('m',time());
        }

        $days = cal_days_in_month(CAL_GREGORIAN,$month,$year);//获取当前月共有多少天
        $date = [];
        $count = [];
        //遍历出当前月的所有天数
        for($i = 0;$i < $days;$i++){
            if($time != 'null'){
                $nowMonth = $time;
            }else{
                $nowMonth = date('Y-m',time());
            }
            $firstday = $nowMonth.'-01'; //拼成当前月的第一天
            $date[] = date('Y-m-d',strtotime($i.' days '.$firstday));

            $start = date('Y-m-d 00:00:00',strtotime($i.' days '.$firstday));
            $end = date('Y-m-d H:i:s',(strtotime($start) + 86400));
            if($orders == 'orders'){
                $count[] = DB::table('orders')->whereBetween('created_at',[$start,$end])->where('isDelete',0)->whereIn('status',[0,1,2,3,4])->count();
            }else{
                $count[] = DB::table('users')->whereIn('type',[0,1])->whereBetween('created_at',[$start,$end])->count();
            }

        }

        //计算当前月的总数
        if($time != 'null'){
            $nowMonthCount = $time;
        }else{
            $nowMonthCount = date('Y-m',time());
        }
        $firstMonthDay = $nowMonthCount.'-01 00:00:00';
        $endMonthDay = date('Y-m-d H:i:s',strtotime($days.' days'.$firstMonthDay));
       if($orders == 'orders'){
           $montbCount = DB::table('orders')->whereBetween('created_at',[$firstMonthDay,$endMonthDay])->where('isDelete',0)->whereIn('status',[0,1,2,3,4])->count();
       }else{
           $montbCount = DB::table('users')->whereIn('type',[0,1])->whereBetween('created_at',[$firstMonthDay,$endMonthDay])->count();
       }

        //总数
        if($orders == 'orders'){
            $totalCount = DB::table('orders')->where('isDelete',0)->whereIn('status',[0,1,2,3,4])->count();
        }else{
            $totalCount = DB::table('users')->whereIn('type',[0,1])->count();
        }

//        dump($count);
//        dd($date);
        $data['categories'] = array_values($date);
        $data['data'] = array_values($count);
        $data['nowMonthCount'] = $montbCount;
        $data['totalCount'] = $totalCount;
        return response()->json($data);
    }



    /**
     * 订单统计展示
     */
    public function orderCountList(){
        $data = [];
        $time = date('Y-m',time());
        for($i = 0;$i < 24;$i++){
            $data[] = date('Y-m',strtotime('-'.$i.' month '.$time));
        }
        return view('admin.count.orderCountList',['data'=>$data]);
    }
}
