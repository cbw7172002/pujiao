<?php

namespace App\Http\Controllers\Admin\baseInfo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use DB;

class classController extends Controller{

    public function classList(Request $request){

       $data = DB::table('schoolclass as s')
           ->leftjoin('schoolgrade as grade','grade.id','=','s.parentId')
           ->select('s.*','grade.gradeName')
           ->where('s.status','=',1)
           ->paginate(10);

       return view('admin.baseInfo.class.classList')->with('data',$data);
    }


    /**
     * 添加页面
     */
    public function addClass(){
        $data = DB::table('schoolgrade')->select('id','gradeName')->get();
        return view('admin.baseInfo.class.addClass')->with('data',$data);
    }


    /**
     * 添加
     */
    public function doAddClass(Request $request){
        $input = Input::except('_token');
        //验证
        $validate = $this->validator($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();
        $res = DB::table('schoolclass')->insertGetId($input);
        if($res){
//            $this -> OperationLog("新增了年级ID为{$res}的信息", 1);
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'baseInfo/classList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }

    }


    /**
     * 编辑页面
     */
    public function editClass($id){

        $data = DB::table('schoolclass')->select()->where('id','=',$id)->first();

        $datas = DB::table('schoolgrade')->select('id','gradeName')->get();


        return view('admin.baseInfo.class.editClass')->with('data',$data)->with('datas',$datas);

    }


    /**
     * 编辑
     */
    public function doEditClass(Request $request){
        $input = Input::except('_token');
        //验证
        $validate = $this->validator_edit($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $res = DB::table('schoolclass')->where('id',$input['id'])->update($input);

        if($res !== false){
//            $this -> OperationLog("修改了后台用户ID为{$request['id']}的信息", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'baseInfo/classList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }


    /**
     * 删除
     */
    public function delClass($id){
        $res = DB::table('schoolclass')->where('id',$id)->delete();
        if($res){
//            $this -> OperationLog("删除了后台用户ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'baseInfo/classList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }



    /**
     * 验证(添加)
     */
    protected function validator(array $data){
        $rules = [
            'classname' => 'required',
            'parentId' => 'required',
        ];
        $messages = [
            'classname.required' => '请输入班级',
            'parentId.required' => '请输入班级',
        ];

        return \Validator::make($data, $rules, $messages);
    }



    /**
     * 验证(修改)
     */
    protected function validator_edit(array $data){
        $rules = [
            'classname' => 'required',
            'parentId' => 'required',
        ];
        $messages = [
            'classname.required' => '请输入班级',
            'parentId.required' => '请输入班级',
        ];

        return \Validator::make($data, $rules, $messages);
    }



}

