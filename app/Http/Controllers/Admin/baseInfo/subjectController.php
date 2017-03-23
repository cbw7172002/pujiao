<?php

namespace App\Http\Controllers\Admin\baseInfo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use DB;
use Cache;


class subjectController extends Controller{

    public function subjectList(Request $request){

       $data = DB::table('studysubject')->select()->paginate(10);

       return view('admin.baseInfo.subject.subjectList')->with('data',$data);
    }


    /**
     * 添加页面
     */
    public function addSubject(){

        return view('admin.baseInfo.subject.addSubject');
    }


    /**
     * 添加
     */
    public function doAddSubject(Request $request){
        $input = Input::except('_token');
        //验证
        $validate = $this->validator($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $res = DB::table('studysubject')->insertGetId($input);
        if($res){
//            $this -> OperationLog("新增了年级ID为{$res}的信息", 1);
            if(Cache::has('App\Http\Controllers\Home\resource\getRessels\2')){
                Cache::forget('App\Http\Controllers\Home\resource\getRessels\2');
            }else{
                $data = DB::table('studysubject')->select('id','subjectName')->orderBy('id','asc')->get();
                Cache::forever('App\Http\Controllers\Home\resource\getRessels\2', $data);
            }
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'baseInfo/subjectList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }

    }


    /**
     * 编辑页面
     */
    public function editSubject($id){

        $data = DB::table('studysubject')->select()->where('id','=',$id)->first();

        return view('admin.baseInfo.subject.editSubject')->with('data',$data);

    }


    /**
     * 编辑
     */
    public function doEditSubject(Request $request){
        $input = Input::except('_token');
        //验证
        $validate = $this->validator_edit($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $res = DB::table('studysubject')->where('id',$input['id'])->update($input);

        if($res !== false){
//            $this -> OperationLog("修改了后台用户ID为{$request['id']}的信息", 1);
            if(Cache::has('App\Http\Controllers\Home\resource\getRessels\2')){
                Cache::forget('App\Http\Controllers\Home\resource\getRessels\2');
            }else{
                $data = DB::table('studysubject')->select('id','subjectName')->orderBy('id','asc')->get();
                Cache::forever('App\Http\Controllers\Home\resource\getRessels\2', $data);
            }
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'baseInfo/subjectList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }


    /**
     * 删除
     */
    public function delSubject($id){
        $res = DB::table('studysubject')->where('id',$id)->delete();
        if($res){
//            $this -> OperationLog("删除了后台用户ID为{$id}的信息", 1);
            if(Cache::has('App\Http\Controllers\Home\resource\getRessels\2')){
                Cache::forget('App\Http\Controllers\Home\resource\getRessels\2');
            }else{
                $data = DB::table('studysubject')->select('id','subjectName')->orderBy('id','asc')->get();
                Cache::forever('App\Http\Controllers\Home\resource\getRessels\2', $data);
            }
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'baseInfo/subjectList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }



    /**
     * 验证(添加)
     */
    protected function validator(array $data){
        $rules = [
            'subjectName' => 'required',
        ];
        $messages = [
            'subjectName.required' => '请输入科目',
        ];

        return \Validator::make($data, $rules, $messages);
    }



    /**
     * 验证(修改)
     */
    protected function validator_edit(array $data){
        $rules = [
            'subjectName' => 'required',
        ];
        $messages = [
            'subjectName.required' => '请输入科目',
        ];

        return \Validator::make($data, $rules, $messages);
    }



}

