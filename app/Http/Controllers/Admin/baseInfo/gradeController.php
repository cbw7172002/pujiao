<?php

namespace App\Http\Controllers\Admin\baseInfo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use DB;
use Cache;

class gradeController extends Controller{


    /**
     *列表
     */
    public function gradeList(){
        $query = DB::table('schoolgrade as s')
            ->leftjoin('studysection as sec','s.parentId','=','sec.id')
            ->select('s.*','sec.sectionName');
        $data = $query->where('status','=',1)->paginate(10);
       return view('admin.baseInfo.grade.gradeList')->with('data',$data);
    }


    /**
     * 添加页面
     */
    public function addGrade(){

        return view('admin.baseInfo.grade.addGrade');
    }


    //Cache::add('App\Http\Controllers\Home\resource\getRessels\1', $data, 1440);
    /**
     * 添加
     */
    public function doAddGrade(Request $request){
        $input = Input::except('_token');
        //验证
        $validate = $this->validator($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();
        $res = DB::table('schoolgrade')->insertGetId($input);
        if($res){
//            $this -> OperationLog("新增了年级ID为{$res}的信息", 1);
            if(Cache::has('App\Http\Controllers\Home\resource\getRessels\1')){
                Cache::forget('App\Http\Controllers\Home\resource\getRessels\1');
            }else{
                $data = DB::table('schoolgrade')->select('id','gradeName')->where('status',1)->orderBy('id','asc')->get();
                Cache::forever('App\Http\Controllers\Home\resource\getRessels\1', $data);
            }
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'baseInfo/gradeList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }

    }


    /**
     * 编辑页面
     */
    public function editGrade($id){
        $data = DB::table('schoolgrade as s')
            ->leftjoin('studysection as sec','s.parentId','=','sec.id')
            ->select('s.*','sec.sectionName')
            ->where('s.id','=',$id)
            ->where('status','=',1)
            ->first();
        return view('admin.baseInfo.grade.editGrade')->with('data',$data);

    }


    /**
     * 编辑
     */
    public function doEditGrade(Request $request){
        $input = Input::except('_token');
        //验证
        $validate = $this->validator_edit($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $input['updated_at'] = Carbon::now();
        $res = DB::table('schoolgrade')->where('id',$input['id'])->update($input);

        if($res !== false){
//            $this -> OperationLog("修改了后台用户ID为{$request['id']}的信息", 1);
            if(Cache::has('App\Http\Controllers\Home\resource\getRessels\1')){
                Cache::forget('App\Http\Controllers\Home\resource\getRessels\1');
            }else{
                $data = DB::table('schoolgrade')->select('id','gradeName')->where('status',1)->orderBy('id','asc')->get();
                Cache::forever('App\Http\Controllers\Home\resource\getRessels\1', $data);
            }
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'baseInfo/gradeList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }


    /**
     * 删除
     */
    public function delGrade($id){
        $res = DB::table('schoolgrade')->where('id',$id)->delete();
        if($res){
//            $this -> OperationLog("删除了后台用户ID为{$id}的信息", 1);
            if(Cache::has('App\Http\Controllers\Home\resource\getRessels\1')){
                Cache::forget('App\Http\Controllers\Home\resource\getRessels\1');
            }else{
                $data = DB::table('schoolgrade')->select('id','gradeName')->where('status',1)->orderBy('id','asc')->get();
                Cache::forever('App\Http\Controllers\Home\resource\getRessels\1', $data);
            }
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'baseInfo/gradeList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }




    /**
     * 验证(添加)
     */
    protected function validator(array $data){
        $rules = [
            'parentId' => 'required',
            'gradeName' => 'required',
        ];
        $messages = [
            'parentId.required' => '请选择学段',
            'gradeName.required' => '请输入年级',
        ];

        return \Validator::make($data, $rules, $messages);
    }



    /**
     * 验证(修改)
     */
    protected function validator_edit(array $data){
        $rules = [
            'parentId' => 'required',
            'gradeName' => 'required',
        ];
        $messages = [
            'parentId.required' => '请选择学段',
            'gradeName.required' => '请输入年级',

        ];

        return \Validator::make($data, $rules, $messages);
    }





}

