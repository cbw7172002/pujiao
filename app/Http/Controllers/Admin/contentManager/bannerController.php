<?php

namespace App\Http\Controllers\Admin\contentManager;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class bannerController extends Controller{

    public function bannerList(Request $request){

        $query = DB::table('banner');
        if($request->type == 1){
            $query = $query->where('id','like','%'.trim($request['search']).'%');
        }
        if($request->type == 2){
            $query = $query->where('title','like','%'.trim($request['search']).'%');
        }

        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('created_at','<=',$request['endTime']);
        }

        $data = $query->orderBy('id','desc')->paginate(10);
        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
        return view('admin.contentManager.banner.bannerList')->with('banner',$data);
    }


    /**
     * 添加
     */
    public function addbanner(){
        return view('admin.contentManager.banner.addbanner');
    }


    /**
     * 添加方法
     */
    public function addsbanner(Request $request){
        $input = Input::except('_token');
        $input['created_at'] = Carbon::now();
        //判断推荐位是否存在
        $isexit = DB::table('banner')->where('sort',$request['sort'])->first();
        if($isexit){
            DB::table('banner')->where('id',$isexit->id)->update(['sort'=>0]);
        }
        if($request->hasFile('path')){ //判断文件是否存在
            if($request->file('path')->isValid()){ //判断文件在上传过程中是否出错
                $name = $request->file('path')->getClientOriginalName();//获取图片名
                $entension = $request->file('path')->getClientOriginalExtension();//上传文件的后缀
                $newname = md5(date('path'.$name)).'.'.$entension;//拼接新的图片名
                if($request->file('path')->move('./admin/image/contentManager/banner',$newname)){
                    $input['path'] = '/admin/image/contentManager/banner/'.$newname;
                }else{
                    return redirect()->back()->withInput()->withErrors('文件保存失败');
                }
            }else{
                return redirect()->back()->withInput()->withErrors('文件在上传过程中出错');
            }
        }
//        dd($input['path']);
        //验证
        $validate = $this->validator($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $res = DB::table('banner')->insertGetId($input);
        if($res){
            $this -> OperationLog("新增了bannerID为{$res}的信息", 1);
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'contentManager/bannerList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }
    }



    /**
     * 编辑
     */
    public function editbanner($id){
        $data = DB::table('banner')->where('id',$id)->first();
        return view('admin.contentManager.banner.editbanner')->with('data',$data);
    }


    /**
     * 编辑方法
     */
    public function editsbanner(Request $request){
        $input = Input::except('_token');
        $input['created_at'] = Carbon::now();
        //判断推荐位是否存在
        $isexit = DB::table('banner')->where('sort',$request['sort'])->first();
        if($isexit){
            DB::table('banner')->where('id',$isexit->id)->update(['sort'=>0]);
        }
        //验证
        $validate = $this->validator_edit($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        if($request->hasFile('path')){ //判断文件是否存在
            if($request->file('path')->isValid()){ //判断文件在上传过程中是否出错
                $name = $request->file('path')->getClientOriginalName();//获取图片名
                $entension = $request->file('path')->getClientOriginalExtension();//上传文件的后缀
                $newname = md5(date('path'.$name)).'.'.$entension;//拼接新的图片名
                if($request->file('path')->move('./admin/image/contentManager/banner',$newname)){
                    $input['path'] = '/admin/image/contentManager/banner/'.$newname;
                }else{
                    return redirect()->back()->withInput()->withErrors('文件保存失败');
                }

            }else{
                return redirect()->back()->withInput()->withErrors('文件在上传过程中出错');
            }
        }
//        dd($input);
        $res = DB::table('banner')->where('id',$input['id'])->update($input);
        if($res){
            $this -> OperationLog("修改了bannerID为{$request['id']}的信息", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'contentManager/bannerList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }



    /**
     * 删除
     */
    public function delbanner($id){
        $res = DB::table('banner')->where('id',$id)->delete();
        if($res){
            $this -> OperationLog("删除了bannerID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'contentManager/bannerList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }



    /**
     * 审核状态
     */
    public function bannerStatus(Request $request){
        $data['status'] = $request['status'];
        $data = DB::table('banner')->where('id',$request['id'])->update($data);
        if($data){
            $this -> OperationLog("修改了bannerID为{$request['id']}的状态", 1);
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
            'title' => 'required',
//            'url' => 'required',
            'path' => 'required',
            'bgColor' => 'required'
        ];
        $messages = [
            'title.required' => '请输入标题名称',
//            'url.required'  => '请按要求填写链接',
            'bgColor.required' => '请添加背景颜色',
            'path.required' => '请上传图片',
        ];


        return \Validator::make($data, $rules, $messages);
    }


    /**
     * 验证(修改)
     */
    protected function validator_edit(array $data){
        $rules = [
            'title' => 'required',
//            'url' => 'required',
            'bgColor' => 'required'
        ];
        $messages = [
            'title.required' => '请输入标题名称',
//            'url.required'  => '请按要求填写链接',
            'bgColor.required' => '请添加背景颜色',
        ];


        return \Validator::make($data, $rules, $messages);
    }



}