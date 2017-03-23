<?php

namespace App\Http\Controllers\Admin\contentmanager;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class newsController extends Controller{


    /**
     * 列表
     */
    public function newsList(Request $request){
        $query = DB::table('news');

        if($request->type == 1){
            $query = $query->where('id','like','%'.trim($request['search']).'%');
        }
        if($request->type == 2){
            $query = $query->where('title','like','%'.trim($request['search']).'%');
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
        return view('admin.contentManager.news.newsList')->with('news',$data);
    }


    /**
     * 添加
     */
    public function addnews(){

        return view('admin.contentManager.news.addnews');
    }



    /**
     * 添加方法
     */
    public function addsnews(Request $request){
        $input = Input::except('_token');
        $input['created_at'] = Carbon::now();
        //判断推荐位是否存在
        $isexit = DB::table('news')->where('sort',$request['sort'])->first();
        if($isexit){
            DB::table('news')->where('id',$isexit->id)->update(['sort'=>0]);
        }
        //验证
        $validate = $this->validator($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
//        dd($input);
        $res = DB::table('news')->insertGetId($input);
        if($res){
            $this -> OperationLog("新增了新闻资讯ID为{$res}的信息", 1);
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'contentManager/newsList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }
    }



    /**
     * 编辑
     */
    public function editnews($id){
        $data = DB::table('news')->where('id',$id)->first();
        return view('admin.contentManager.news.editnews')->with('data',$data);
    }



    /**
     * 编辑方法
     */
    public function editsnews(Request $request){
        $input = Input::except('_token');
        $input['created_at'] = Carbon::now();
        //判断推荐位是否存在
        $isexit = DB::table('news')->where('sort',$request['sort'])->first();
        if($isexit){
            DB::table('news')->where('id',$isexit->id)->update(['sort'=>0]);
        }
        //验证
        $validate = $this->validator_edit($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $res = DB::table('news')->where('id',$input['id'])->update($input);
        if($res){
            $this -> OperationLog("修改了新闻资讯ID为{$request['id']}的信息", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'contentManager/newsList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }



    /**
     * 删除
     */
    public function delnews($id){
        $res = DB::table('news')->where('id',$id)->delete();
        if($res){
            $this -> OperationLog("删除了新闻资讯ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'contentManager/newsList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }



    /**
     * 审核状态
     */
    public function newsStatus(Request $request){
        $data['status'] = $request['status'];
        $data = DB::table('news')->where('id',$request['id'])->update($data);
        if($data){
            $this -> OperationLog("修改了新闻资讯ID为{$request['id']}的状态", 1);
            echo 1;
        }else{
            echo 0;
        }
    }



    /**
     * 验证
     */
    protected function validator(array $data){
        $rules = [
            'title' => 'required',
            'content' => 'required',
            'description' => 'required'
        ];
        $messages = [
            'title.required' => '请输入标题名称',
            'content.required'  => '请输入内容',
            'description.required' => '请输入新闻描述'
        ];


        return \Validator::make($data, $rules, $messages);
    }



    protected function validator_edit(array $data){
        $rules = [
            'content' => 'required',
            'description' => 'required'
        ];
        $messages = [
            'content.required'  => '请输入内容',
            'description.required' => '请输入新闻描述'
        ];


        return \Validator::make($data, $rules, $messages);
    }





}