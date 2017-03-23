<?php

namespace App\Http\Controllers\Admin\complaint;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class complaintController extends Controller{


    /**
     * 列表页
     */
    public function complaintList(Request $request){
        $hidden = $request['hidden'];

        $query = DB::table('complaint as com')
            ->leftjoin('users as u','com.uid','=','u.id')
            ->select('u.username','com.*');
        if($hidden == ''){
            $query = $query;
        }
        if($hidden == 1){
            $query = $query->where('com.status','=',0);
        }


        $data = $query->orderBy('com.id','desc')->paginate(10);
        $data->type = $hidden;

//        dd($complain);
        return view('admin.complaint.complaintList')->with('data',$data);
    }



    /**
     * 删除
     */
    public function delcomplaint($id){
        $res = DB::table('complaint')->where('id',$id)->delete();
        if($res){
            $this -> OperationLog("删除了意见反馈ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'complaint/complaintList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }


    /**
     * 未处理--已处理
     */
    public function complaintStatus(Request $request){
        $data['status'] = $request['status'];

        $data = DB::table('complaint')->where('id',$request['id'])->update($data);
        if($data){
            $this -> OperationLog("修改了意见反馈ID为{$request['id']}的状态", 1);
            echo 1;
        }else{
            echo 0;
        }
    }



}