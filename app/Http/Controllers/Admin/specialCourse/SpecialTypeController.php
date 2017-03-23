<?php

namespace App\Http\Controllers\Admin\specialCourse;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class SpecialTypeController extends Controller
{
    /**
     * 类型列表
     */
    public function specialTypeList(){
        $data = DB::table('coursetype')->orderBy('id','desc')->paginate(15);
        return view('admin/specialCourse/specialTypeList',['data'=>$data]);
    }

    /**
     *添加类型
     */
    public function addSpecialType(){
        return view('admin/specialCourse/addSpecialType');
    }

    /**
     *执行添加
     */
    public function doAddSpecialType(Request $request){
        $this->validate($request,[
            'typeName'=>'required|max:5'
        ],[
            'typeName.required'=>'请输入课程类型',
            'typeName.max'=>'课程类型长度不超过5'
        ]);
        $data = $request->except('_token');
        if($id = DB::table('coursetype')->where('id',$request['id'])->insertGetId($data)){
            $this -> OperationLog('添加了id为'.$id.'的课程类型');
            return redirect('admin/message')->with(['status'=>'课程类型添加成功','redirect'=>'specialCourse/specialTypeList']);
        }else{
            return redirect('admin/message')->with(['status'=>'课程类型添加失败','redirect'=>'specialCourse/specialTypeList']);
        }
    }

    /**
     *课程类型编辑
     */
    public function editSpecialType($id){
        $data = DB::table('coursetype')->where('id',$id)->first();
        return view('admin/specialCourse/editSpecialType',['data'=>$data]);
    }

    /**
     *执行编辑
     */
    public function doEditSpecialType(Request $request){
        $this->validate($request,[
            'typeName'=>'required|max:5'
        ],[
            'typeName.required'=>'请输入课程类型',
            'typeName.max'=>'课程类型长度不超过5'
        ]);
        $data = $request->except('_token');
        if(DB::table('coursetype')->where('id',$request['id'])->update($data)){
            $this -> OperationLog('修改了id为'.$request['id'].'的课程类型');
            return redirect('admin/message')->with(['status'=>'课程类型编辑成功','redirect'=>'specialCourse/specialTypeList']);
        }else{
            return redirect('admin/message')->with(['status'=>'课程类型编辑失败','redirect'=>'specialCourse/specialTypeList']);
        }
    }

    /**
     *删除
     */
    public function delSpecialType($id){
        if(DB::table('coursetype')->where('id',$id)->delete()){
            $this -> OperationLog('删除了id为'.$id.'的课程类型');
            return redirect('admin/message')->with(['status'=>'课程类型删除成功','redirect'=>'specialCourse/specialTypeList']);
        }else{
            return redirect('admin/message')->with(['status'=>'课程类型删除失败','redirect'=>'specialCourse/specialTypeList']);
        }
    }
}
