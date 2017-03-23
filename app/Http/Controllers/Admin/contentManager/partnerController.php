<?php

namespace App\Http\Controllers\Admin\contentManager;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;


class partnerController extends Controller{

    public function partnerList(Request $request){

        $query = DB::table('partner');
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
        return view('admin.contentManager.partner.partnerList')->with('partner',$data);
    }



    /**
     * 添加
     */
    public function addpartner(){
        return view('admin.contentManager.partner.addpartner');
    }


    /**
     * 添加方法
     */
    public function addspartner(Request $request){
        $input = Input::except('_token');
        $input['created_at'] = Carbon::now();
        if($request->hasFile('path')){ //判断文件是否存在
            if($request->file('path')->isValid()){ //判断文件在上传过程中是否出错
                $name = $request->file('path')->getClientOriginalName();//获取图片名
                $entension = $request->file('path')->getClientOriginalExtension();//上传文件的后缀
                $newname = md5(date('path'.$name)).'.'.$entension;//拼接新的图片名
                if($request->file('path')->move('./admin/image/contentManager/partner',$newname)){
                    $input['path'] = '/admin/image/contentManager/partner/'.$newname;
                }else{
                    return redirect()->back()->withInput()->withErrors('文件保存失败');
                }

            }else{
                return redirect()->back()->withInput()->withErrors('文件在上传过程中出错');
            }
        }
        //判断推荐位是否存在
        $isexit = DB::table('partner')->where('postion',$request['postion'])->first();
        if($isexit){
            DB::table('partner')->where('id',$isexit->id)->update(['postion'=>0]);
        }
        //验证
        $validate = $this->validator($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $res = DB::table('partner')->insertGetId($input);
        if($res){
            $this -> OperationLog("新增了合作伙伴ID为{$res}的信息", 1);
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'contentManager/partnerList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }
    }



    /**
     * 编辑
     */
    public function editpartner($id){
        $data = DB::table('partner')->where('id',$id)->first();
        return view('admin.contentManager.partner.editpartner')->with('data',$data);
    }


    /**
     * 编辑方法
     */
    public function editspartner(Request $request){
        $input = Input::except('_token');
        $input['created_at'] = Carbon::now();
        //判断推荐位是否存在
        $isexit = DB::table('partner')->where('postion',$request['postion'])->first();
        if($isexit){
            DB::table('partner')->where('id',$isexit->id)->update(['postion'=>0]);
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
                if($request->file('path')->move('./admin/image/contentManager/partner',$newname)){
                    $input['path'] = '/admin/image/contentManager/partner/'.$newname;
                }else{
                    return redirect()->back()->withInput()->withErrors('文件保存失败');
                }

            }else{
                return redirect()->back()->withInput()->withErrors('文件在上传过程中出错');
            }
        }
        $res = DB::table('partner')->where('id',$input['id'])->update($input);
        if($res){
            $this -> OperationLog("修改了合作伙伴ID为{$request['id']}的信息", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'contentManager/partnerList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }



    /**
     * 删除
     */
    public function delpartner($id){
        $res = DB::table('partner')->where('id',$id)->delete();
        if($res){
            $this -> OperationLog("删除了合作伙伴ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'contentManager/partnerList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }




    /**
     * 审核状态
     */
    public function partnerStatus(Request $request){
        $data['status'] = $request['status'];
        $data = DB::table('partner')->where('id',$request['id'])->update($data);
        if($data){
            $this -> OperationLog("修改了合作伙伴ID为{$request['id']}的状态", 1);
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
            'url' => 'required',
            'path' => 'required'
        ];
        $messages = [
            'title.required' => '请输入标题名称',
            'url.required'  => '请按要求填写链接',
            'path.required' => '请上传图片'
        ];


        return \Validator::make($data, $rules, $messages);
    }


    protected function validator_edit(array $data){
        $rules = [
            'title' => 'required',
            'url' => 'required',
        ];
        $messages = [
            'title.required' => '请输入标题名称',
            'url.required'  => '请按要求填写链接',
        ];


        return \Validator::make($data, $rules, $messages);
    }



}