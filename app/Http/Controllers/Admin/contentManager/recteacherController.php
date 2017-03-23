<?php

namespace App\Http\Controllers\Admin\contentManager;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;


class recteacherController extends Controller{

    /**
     * 列表
     */
    public function recteacherList(){
        $data = DB::table('recteacher as rec')->orderBy('sort','asc')->orderBy('id','desc')->get();
        return view('admin.contentManager.recteacher.recteacherList')->with('recteacher',$data);
    }

    /**
     * 添加
     */
    public function addrecteacher(){
        $teacher = DB::table('teacher as t')
                ->leftjoin('users as u','u.id','=','parentId')
                ->select('u.id','u.realname')
                ->get();
        return view('admin.contentManager.recteacher.addrecteacher')->with('recteacher',$teacher);
    }



    /**
     * 添加方法
     */
    public function addsrecteacher(Request $request){
        $input = Input::except('_token');
        if(DB::table('recteacher')->where('userId',$request['userId'])->first()){
            return redirect()->back()->withInput()->withErrors('名师不可重复推荐');
        }
        $input['created_at'] = Carbon::now();

        //判断推荐位是否存在
        $isexit = DB::table('recteacher')->where('sort',$request['sort'])->first();
        if($isexit){
            DB::table('recteacher')->where('id',$isexit->id)->update(['sort'=>0]);
        }

        $teachername = DB::table('users')->where('id',$request['userId'])->select('realname')->first();
        $input['name'] = $teachername->realname;
        $count = DB::table('recteacher')->count();
        if($count >= 5){
            return redirect()->back()->withInput()->withErrors('社区名师推荐最多5条数据！');
        }else{
            $res = DB::table('recteacher')->insertGetId($input);
        }
        if($res){
            $this -> OperationLog("新增了社区名师推荐ID为{$res}的信息", 1);
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'contentManager/recteacherList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }
    }



    /**
     *编辑
     */
    public function editrecteacher($id){
        $data = DB::table('recteacher')->where('id',$id)->first();
        return view('admin.contentManager.recteacher.editrecteacher')->with('data',$data);
    }


    /**
     * 编辑方法
     */
    public function editsrecteacher(Request $request){
        $input = Input::except('_token');
        $input['updated_at'] = Carbon::now();
        //判断推荐位是否存在
        $isexit = DB::table('recteacher')->where('sort',$request['sort'])->first();
        if($isexit){
            DB::table('recteacher')->where('id',$isexit->id)->update(['sort'=>0]);
        }
        $res = DB::table('recteacher')->where('id',$input['id'])->update($input);
        if($res){
            $this -> OperationLog("修改了社区名师推荐ID为{$request['id']}的信息", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'contentManager/recteacherList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }



    /**
     * 删除
     */
    public function deleterecteacher($id){
        if(DB::table('recteacher')->where('id',$id)->delete()){
            $this -> OperationLog("删除了社区名师推荐D为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'contentManager/recteacherList']);
        }else{
            return redirect('admin/message')->with(['status'=>'删除失败','redirect'=>'contentManager/recteacherList']);
        }
    }



}