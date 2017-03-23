<?php

namespace App\Http\Controllers\Admin\companyUser;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class companyUserController extends Controller{

    /**
     * 列表
     */
    public function companyUserList(Request $request){


        $query = DB::table('users as u')
                ->leftjoin('department as dep','dep.id','=','u.departId')
                ->leftjoin('post as p','p.id','=','u.postId')
                ->select('dep.departName','p.postName','u.id','u.username','u.realname','u.phone','u.email','u.checks','u.created_at')
                ->where('type',3);

        if($request->type == 1){
            $query = $query->where('u.realname','like','%'.trim($request['search']).'%');
        }
        if($request->type == 2){
            $query = $query->where('dep.departName','like','%'.trim($request['search']).'%');
        }
        if($request->type == 3){
            $query = $query->where('p.postName','like','%'.trim($request['search']).'%');
        }
        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('acc.created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('acc.created_at','<=',$request['endTime']);
        }

        $data = $query->orderBy('id','desc')->paginate(10);
        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
        return view('admin.companyUser.companyUserList')->with('company',$data);
    }


    /**
     * 添加
     */
    public function addcompanyUser(){
        $depart = DB::table('department')->get();
        return view('admin.companyUser.addcompanyUser')->with('depart',$depart);
    }


    /**
     * 添加方法
     */
    public function addscompanyUser(Request $request){
        $input = Input::except('_token');
        $input['type'] = '3';
        //验证
        $validate = $this->validator($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $input['created_at'] = Carbon::now();
        if(($input['password']) == $input['upassword'] ){
            $input['password'] = bcrypt($input['password']);
            //销毁upassword字段
            unset($input['upassword']);
            $res = DB::table('users')->insertGetId($input);
        }else{
            return redirect()->back()->withInput()->withErrors('与第一次输入的密码不同，请重新输入');
        }
        if($res){
            $this -> OperationLog("新增了后台用户ID为{$res}的信息", 1);
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'companyUser/companyUserList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }
    }


    /**
     * 编辑
     */
    public function editcompanyUser($id){
        $data = DB::table('users')->where('id',$id)->first();
        $depart = DB::table('department')->get();
        $post = DB::table('post')->get();
        return view('admin.companyUser.editcompanyUser')->with('data',$data)->with('depart',$depart)->with('post',$post);
    }


    /**
     * 编辑方法
     */
    public function editscompanyUser(Request $request){
        $input = Input::except('_token');
        $input['type'] = '3';
        // $input['updated_at'] = Carbon::now();
//        dd($input);
        //验证
        $validate = $this->validator_edit($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $res = DB::table('users')->where('id',$input['id'])->update($input);

        if($res !== false){
            $this -> OperationLog("修改了后台用户ID为{$request['id']}的信息", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'companyUser/companyUserList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }




    /**
     * 删除
     */
    public function delcompanyUser($id){
        $res = DB::table('users')->where('id',$id)->delete();
        if($res){
            $this -> OperationLog("删除了后台用户ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'companyUser/companyUserList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }



    /**
     * 重置密码页面
     */
    public function resetPassword($id){
        $data = DB::table('users')->where('id',$id)->first();
        return view('admin.companyUser.editresetPassword')->with('data',$data);
    }


    /**
     * 提交密码
     */
    public function resetsPassword(Request $request){
        $input = Input::except('_token');
        //验证
        $validate = $this->validator_pass($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }

        if(($input['password']) == $input['upassword'] ){
            $input['password'] = bcrypt($input['password']);
            //销毁upassword字段
            unset($input['upassword']);
            $res = DB::table('users')->where('id',$input['id'])->update($input);
        }else{
            return redirect()->back()->withInput()->withErrors('与第一次输入的密码不同，请重新输入');
        }
        if($res){
            $this -> OperationLog("修改了后台用户ID为{$request['id']}的密码", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'companyUser/companyUserList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }



    /**
     * depart---post
     */
    public function departPost(Request $request){
        $data = DB::table('post')->where('parentId',$request['id'])->get();
        echo json_encode($data);
    }



    /**
     * 状态
     */
    public function companyStatus(Request $request){
        $data['checks'] = $request['status'];
        $data = DB::table('users')->where('id',$request['id'])->update($data);
        if($data){
            $this -> OperationLog("修改了后台用户ID为{$request['id']}的状态", 1);
            echo 1;
        }else{
            echo 0;
        }
    }






    /**
     * 验证(添加)
     */
    protected function validator(array $data){
        $rules = [
            'username' => 'required|min:3|max:16',
            'realname' => 'required',
            'password' => 'sometimes|required|min:6|max:16',
            'phone' => 'required|digits:11',
            'email' => 'required',
            'departId' => 'required',
            'postId' => 'required',
        ];
        $messages = [
            'username.required' => '请输入用户名',
            'username.min' => '用户名最少3位',
            'username.max' => '用户名最多16位',
            'realname.required' => '请输入姓名',
            'password.required' => '请输入密码',
            'password.min' => '密码最少6位',
            'password.max' => '密码最多16位',
            'phone.required' => '请输入手机号',
            'phone.digits' => '手机号为11位数字',
            'email.required' => '请输入邮箱',
            'departId.required' => '请选择部门',
            'postId.required' => '请选择岗位',
        ];

        return \Validator::make($data, $rules, $messages);
    }



    /**
     * 验证(修改)
     */
    protected function validator_edit(array $data){
        $rules = [
            'realname' => 'required',
            'phone' => 'required|digits:11',
            'email' => 'required',
            'departId' => 'required',
            'postId' => 'required',
        ];
        $messages = [
            'realname.required' => '请输入姓名',
            'phone.required' => '请输入手机号',
            'phone.digits' => '手机号为11位数字',
            'email.required' => '请输入邮箱',
            'departId.required' => '请选择部门',
            'postId.required' => '请选择岗位',
        ];

        return \Validator::make($data, $rules, $messages);
    }


    /**
     * 验证密码
     */
    protected function validator_pass(array $data){
        $rules = [
            'password' => 'sometimes|required|min:6|max:16',
        ];
        $messages = [
            'password.required' => '请输入密码',
            'password.min' => '密码最少6位',
            'password.max' => '密码最多16位',
        ];

        return \Validator::make($data, $rules, $messages);
    }


}