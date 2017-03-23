<?php

namespace App\Http\Controllers\admin\activity;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class activityController extends Controller{


    /**
     * 赛事列表
     */
    public function activityList(Request $request){
        //搜索
        $query = DB::table('activity');
        if($request->type == 1){
            $query = $query->where('status',0);
        }
        if($request->type == 2){
            $query = $query->where('status',1);
        }
        if($request->type == 3){
            $query = $query->where('status',2);
        }
        if($request->type == 4){
            $query = $query->where('status',3);
        }
        if($request->type == 5){
            $query = $query->where('id','like','%'.trim($request['search']).'%');
        }
        if($request->type == 6){
            $query = $query->where('title','like','%'.trim($request['search']).'%');
        }
        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('beginTime','<=',$request['endTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('endTime','>=',$request['beginTime']);
        }

    	$data = $query->orderBy('id','desc')->paginate(10);
        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
        return view('admin.activity.activityList')->with('activity',$data);
    }

    /**
     * 添加赛事
     */
    public function addactivity(){

        return view('admin.activity.addactivity');
    }


    /**
     * 添加赛事方法
     */
    public function addsactivity(Request $request){
        $input = Input::except('_token');
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();
        //验证
        $validate = $this->validator($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        if($request->hasFile('path')){ //判断文件是否存在
            if($request->file('path')->isValid()){ //判断文件在上传过程中是否出错
                $name = $request->file('path')->getClientOriginalName();//获取图片名
                $entension = $request->file('path')->getClientOriginalExtension();//上传文件的后缀
                $newname = md5(date('path'.$name)).'.'.$entension;//拼接新的图片名
                if($request->file('path')->move('./admin/image/activity',$newname)){
                    $input['path'] = 'admin/image/activity/'.$newname;
                }else{
                    return redirect()->back()->withInput()->withErrors('文件保存失败');
                }

            }else{
                return redirect()->back()->withInput()->withErrors('文件在上传过程中出错');
            }
        }
       // dd($input);
        if($res = DB::table('activity')->insertGetId($input)){
            $this -> OperationLog("新增了赛事管理ID为{$res}的信息", 1);
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'activity/activityList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }
    }


    /**
     * 编辑
     */
    public function editactivity($id){
        $res = DB::table('activity')->where('id',$id)->first();
        return view('admin.activity.editactivity')->with('data',$res);
    }


    /**
     * 编辑方法
     */
    public function editsactivity(Request $request){
        $input = Input::except('_token');
        $input['updated_at'] = Carbon::now();
        $validate = $this->validator($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        if($request->hasFile('path')){ //判断文件是否存在
            if($request->file('path')->isValid()){ //判断文件在上传过程中是否出错
                $name = $request->file('path')->getClientOriginalName();//获取图片名
                $entension = $request->file('path')->getClientOriginalExtension();//上传文件的后缀
                $newname = md5(date('path'.$name)).'.'.$entension;//拼接新的图片名
                if($request->file('path')->move('./admin/image/activity',$newname)){
                    $input['path'] = '/admin/image/activity/'.$newname;
                }else{
                    return redirect()->back()->withInput()->withErrors('文件保存失败');
                }

            }else{
                return redirect()->back()->withInput()->withErrors('文件在上传过程中出错');
            }
        }
        $res = DB::table('activity')->where('id',$input['id'])->update($input);
        if($res){
            $this -> OperationLog("修改了赛事管理ID为{$request['id']}的信息", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'activity/activityList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }




    /**
     * 删除
     */
    public function delactivity($id){
        $res = DB::table('activity')->where('id',$id)->delete();
        if($res){
            $this -> OperationLog("删除了赛事管理ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'activity/activityList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }



    /**
     * 活动状态
     */
    public function activityStatus(Request $request){
        $data['status'] = $request['status'];
        $data['updated_at'] = Carbon::now();
        $data = DB::table('activity')->where('id',$request['id'])->update($data);
        if($data){
            $this -> OperationLog("修改了赛事管理ID为{$request['id']}的状态", 1);
            echo 1;
        }else{
            echo 0;
        }
    }



    //图片上传
    public function upload(){
        $file = Input::file('path');
        if($file -> isValid()){
            $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
            $newName = date('YmdHis').mt_rand(100,999).'.'.$entension;
            $path = $file -> move('./admin/image/activity',$newName);
            $filepath = 'admin/image/activity/'.$newName;
            return $filepath;
        }
    }



    /**
     * 验证(添加)
     */
    protected function validator(array $data){
        $rules = [
            'title' => 'required',
            'url' => 'required',
            'activityRrom' => 'required',
            'beginTime' => 'required',
            'endTime' => 'required'
        ];
        $messages = [
            'title.required' => '请输入标题名称',
            'url.required'  => '请按要求填写链接',
            'activityRrom.required' => '请填写举办方名称',
            'beginTime.required' => '请填写活动开始时间',
            'endTime.required' => '请填写活动介绍时间'
        ];


        return \Validator::make($data, $rules, $messages);
    }




}
