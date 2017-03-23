<?php

namespace App\Http\Controllers\Admin\subject;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;



class subjectController extends Controller
{
    //列表
    public function subjectList(Request $request){
        $query = DB::table('subjects');

        if($request['type'] == 1){
            $query = $query->where('id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('subjectname','like','%'.trim($request['search']).'%');
        }

        $data = $query
            ->orderBy('id','desc')
            ->paginate(15);
        $data->type = $request['type'];
        return view('admin.subject.subjectList',['data'=>$data]);
    }


    //添加页
    public function addsubject(){
        return view('admin.subject.addsubject');
    }

//<select name="" id="">
//@foreach()
//<option value=""></option>
//@endforeach
//</select>

    //添加功能
    public function addssubject(Request $request){
        $input = Input::except('_token');
        $subjectname = $request['subjectname'];
        $subject = DB::table('subjects')->select('subjectname')->get();
        //验证
        $validate = $this->validator($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        //验证是否有重名的科目
        foreach($subject as $key => $val){
           foreach($val as $v){

           }
           $arr[] = $v;
        }
        $res = in_array($subjectname,$arr);
        if($res == true){
            return redirect()->back()->withInput()->withErrors('此科目已存在！');
        }
        $input['created_at'] = Carbon::now();
        if($res = DB::table('subjects')->insertGetId($input)){
            $this -> OperationLog("新增了科目管理ID为{$res}的信息", 1);
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'subject/subjectList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }
    }



    //编辑
    public function editsubject($id){
        $res = DB::table('subjects')->where('id',$id)->first();
        return view('admin.subject.editsubject')->with('data',$res);
    }


    /**
     * 编辑方法
     */
    public function editssubject(Request $request){
        $input = Input::except('_token');
        $subjectname = $request['subjectname'];
        $subject = DB::table('subjects')->select('subjectname')->get();
        //验证
        $validate = $this->validator_edit($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        //验证是否有重名的科目
//        foreach($subject as $key => $val){
//            foreach($val as $v){
//
//            }
//            $arr[] = $v;
//        }
//        $res = in_array($subjectname,$arr);
//        if($res == true){
//            return redirect()->back()->withInput()->withErrors('此科目已存在！');
//        }
        $input['updated_at'] = Carbon::now();
        $res = DB::table('subjects')->where('id',$input['id'])->update($input);
        if($res){
            $this -> OperationLog("新增了科目管理ID为{$res}的信息", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'subject/subjectList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }




    /**
     * 删除
     */
    public function delsubject($id){
        $res = DB::table('subjects')->where('id',$id)->delete();
        if($res){
            $this -> OperationLog("删除了科目管理ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'subject/subjectList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }



    //验证唯一性
    public function name_unique(Request $request){
        $name = $request['name'];
        $data = DB::table('subjects')->where('subjectname',$name)->get();
        if($data){
            return ['status' => 1];
        }else{
            return ['status' => 0];
        }

    }



    /**
     * 验证(添加)
     */
    protected function validator(array $data){
        $rules = [
            'subjectname' => 'required',
        ];
        $messages = [
            'subjectname.required' => '请输入科目',
        ];

        return \Validator::make($data, $rules, $messages);
    }



    /**
     * 验证(修改)
     */
    protected function validator_edit(array $data){
        $rules = [
            'subjectname' => 'required',
        ];
        $messages = [
            'subjectname.required' => '请输入科目',
        ];

        return \Validator::make($data, $rules, $messages);
    }



}