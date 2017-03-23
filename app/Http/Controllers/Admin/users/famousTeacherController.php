<?php

namespace App\Http\Controllers\Admin\users;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class famousTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //名师列表

        //定义搜索初值
        $search = [];
        //搜索
        $query = DB::table('users as u')
            ->leftJoin('teacher as t','u.id','=','t.parentId')
            ->select('u.*','u.id as userId','u.username as name','t.price','t.stock','t.intro','t.cover');

        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('u.created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('u.created_at','<=',$request['endTime']);
        }

        if($request->type == 1){
            $query = $query->where('u.username','like','%'.trim($request->search).'%');
        }
        if($request->type == 2){
            $query = $query->where('u.realname','like','%'.trim($request->search).'%');
        }
        if($request->type == 3){
            $query = $query->where('u.phone','like','%'.trim($request->search).'%');
        }

        $data = $query->where('u.type','=',2)->orderBy('u.id','desc')->paginate(10);
        foreach($data as &$val){
            $val->price = $val->price / 100;
        }
        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
//        dd($data);
        return view('admin.users.famousTeacherList',compact('data','search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //分配名师
        //省市数据，加载至模板
        $province = DB::table('province')->get();
        $letter  = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        return view('admin.users.addFamousTeacher',compact('province'),['letter'=>$letter]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function insert(Request $request)
    {
        $data = $request->except('_token');
        //调用验证
        $validate = $this->validator($data);
        //验证信息
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $data = $request->except('_token','price','stock','firstletter','cover','intro');
        //加密
        $data['password'] = bcrypt($data['password']);
        $data['checks'] = 0;
        $data['type'] = 2;
        $data['pic'] || $data['pic'] = '/home/image/layout/default.png';
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        //名师初始值
        $famous = [];
        $famous['intro'] = $request->get('intro');
        $famous['price'] = $request['price'] * 100;
        $famous['stock'] = $request['stock'];
        $famous['firstletter'] = $request['firstletter'];



        //上传封面图
        if($request->hasFile('cover')){ //判断文件是否存在
            if($request->file('cover')->isValid()){ //判断文件在上传过程中是否出错
                $name = $request->file('cover')->getClientOriginalName();//获取图片名
                $entension = $request->file('cover')->getClientOriginalExtension();//上传文件的后缀
                $newname = md5(date('ymdhis'.$name)).'.'.$entension;//拼接新的图片名
                if($request->file('cover')->move('./home/image/community',$newname)){
                    $famous['cover'] = '/home/image/community/'.$newname;
                }else{
                    return redirect()->back()->withInput()->withErrors('文件保存失败');
                }

            }else{
                return redirect()->back()->withInput()->withErrors('文件在上传过程中出错');
            }
        }else{
            return redirect()->back()->withInput()->withErrors('请上传名师封面图');
        }

        //user表里添加数据

        if($id = DB::table('users')->insertGetId($data)){
            //向名师表插入数据
            $famous['parentId'] = $id;
            $this -> OperationLog("新增加了用户ID为{$id}的名师", 1);
            if(DB::table('teacher')->insert($famous)){
                return redirect('admin/message')->with(['status'=>'分配名师成功','redirect'=>'users/famousTeacherList']);
            }else{
                return redirect('admin/message')->with(['status'=>'分配名师失败','redirect'=>'users/famousTeacherList']);
            }
        }else{
            return redirect('admin/message')->with(['status'=>'添加用户失败','redirect'=>'users/famousTeacherList']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editFamousTeacher($id)
    {
        //修改数据
        $data = User::findOrFail($id);
        $data->pic || $data->pic = '/home/image/layout/default.png';
        //省份
        $data->province = DB::table('province')->get();
        //市区
        $data->city = DB::table('city')->where(['status'=>0,'provincecode'=>$data->provinceId])->get();

        $data->teacherinfo = DB::table('teacher')->where('parentId',$id)->first();
        $data->teacherinfo->price = $data->teacherinfo->price / 100;
//        dd($data);
        $letter  = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        return view('admin.users.editFamousTeacher',compact('data'),['letter'=>$letter]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function doEditFamousTeacher(Request $request)
    {
        $input = $request->except('_token');
        $validate = $this->validator($input);
        //验证信息
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $input = $request->except('_token','price','stock','firstletter','intro','cover');

        //头像为空，去除
        if(!$input['pic']){
            unset($input['pic']);
        }

        $famous['price'] = $request['price'] * 100;
        $famous['stock'] = $request['stock'];
        $famous['firstletter'] = $request['firstletter'];
        $famous['intro'] = $request['intro'];

        //上传封面图
        if($request->hasFile('cover')){ //判断文件是否存在
            if($request->file('cover')->isValid()){ //判断文件在上传过程中是否出错
                $name = $request->file('cover')->getClientOriginalName();//获取图片名
                $entension = $request->file('cover')->getClientOriginalExtension();//上传文件的后缀
                $newname = md5(date('ymdhis'.$name)).'.'.$entension;//拼接新的图片名
                if($request->file('cover')->move('./home/image/community',$newname)){
                    $famous['cover'] = '/home/image/community/'.$newname;
                }else{
                    return redirect()->back()->withInput()->withErrors('文件保存失败');
                }

            }else{
                return redirect()->back()->withInput()->withErrors('文件在上传过程中出错');
            }
        }

        if(FALSE !== DB::table('users')->where('id',$request['id'])->update($input)){
            DB::table('teacher')->where('parentId',$request['id'])->update($famous);
            $this -> OperationLog("修改了用户ID为{$request['id']}的名师信息", 1);
            return redirect('admin/message')->with(['status'=>'修改名师信息成功','redirect'=>'users/famousTeacherList']);
        }else{
            return redirect('admin/message')->with(['status'=>'修改用户名师失败','redirect'=>'users/famousTeacherList']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delFamousTeacher($id)
    {
        //删除用户
        if(DB::table('users')->delete($id)){
            $this -> OperationLog("删除了用户ID为{$id}的名师", 1);
            if($rec = DB::table('teacher')->where('parentId',$id)->first()){
                //名师表存在即可删除
                DB::table('teacher')->where('id',$rec->id)->delete();
                if(DB::table('hotteacher')->where('teacherId',$id)->first()){
                    //推荐名师存在，即可删除
                    DB::table('hotteacher')->where('teacherId',$id)->delete();
                }
            }
            return redirect('admin/message')->with(['status'=>'删除名师成功','redirect'=>'users/famousTeacherList']);
        }else{
            return redirect('admin/message')->with(['status'=>'删除名师失败','redirect'=>'users/famousTeacherList']);
        }
    }

    /**
     * verify the specified resource from storage.
     *
     */
    protected function validator(array $data)
    {
        $rules = [
            'username' => 'required|min:2|max:16',
            'realname' => 'required',
            'password' => 'sometimes|required|min:6|max:16',
            'phone' => 'required|digits:11',
            'sex' => 'required',
//            'price'=>'required|integer',
            'price'=>'required',
            'stock'=>'required|integer',
            'intro'=>'required|max:200'

        ];

        $messages = [
            'username.required' => '请输入用户名',
            'username.min' => '用户名最少2位',
            'username.max' => '用户名最多16位',
            'realname.required' => '请输入姓名',
            'password.required' => '请输入密码',
            'password.min' => '密码最少6位',
            'password.max' => '密码最多16位',
            'phone.required' => '请输入手机号',
            'phone.digits' => '手机号为11位数字',
            'sex.required' => '性别不能为空',
            'price.required'=>'请输入价格',
//            'price.integer'=>'价格为整型',
            'stock.required'=>'请输入点评名额',
            'stock.integer'=>'名额为整型',
            'intro.required'=>'请输入名师介绍',
            'intro.max'=>'名师介绍不超过200字',
        ];

        return \Validator::make($data, $rules, $messages);
    }
}
