<?php

namespace App\Http\Controllers\Admin\contentmanager;

use QrCode;
use PaasUser;
use PaasResource;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Http\Controllers\Home\lessonComment\Gadget;


class hotvideoController extends Controller{
    use Gadget;

    public static $errMessage;

    public function __construct()
    {
        PaasUser::apply();
    }

    public function hotvideoList(Request $request){
        $query = DB::table('hotvideo');

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
        return view('admin.contentManager.hotvideo.hotvideoList')->with('hotvideo',$data);
    }


    /**
     * 添加
     */
    public function addhotvideo(){
        return view('admin.contentManager.hotvideo.addhotvideo');
    }


    /**
     * 添加方法
     */
    public function addshotvideo(Request $request){
        $input = Input::except('_token');
        $input['created_at'] = Carbon::now();
        $input['status'] = '0';
        $input['courseView'] = '0';

        //判断推荐位是否存在
        $isexit = DB::table('hotvideo')->where('sort',$request['sort'])->first();
        if($isexit){
            DB::table('hotvideo')->where('id',$isexit->id)->update(['sort'=>0]);
        }
        if($request->hasFile('cover')){ //判断文件是否存在
            if($request->file('cover')->isValid()){ //判断文件在上传过程中是否出错
                $name = $request->file('cover')->getClientOriginalName();//获取图片名
                $entension = $request->file('cover')->getClientOriginalExtension();//上传文件的后缀
                $newname = md5(date('cover'.$name)).'.'.$entension;//拼接新的图片名
                if($request->file('cover')->move('./admin/image/contentManager/hotvideo',$newname)){
                    $input['cover'] = '/admin/image/contentManager/hotvideo/'.$newname;
                }else{
                    return redirect()->back()->withInput()->withErrors('文件保存失败');
                }

            }else{
                return redirect()->back()->withInput()->withErrors('文件在上传过程中出错');
            }
        }
//        dd($input);
        //验证
        $validate = $this->validator($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $res = DB::table('hotvideo')->insertGetId($input);
        if($res){
            $this -> OperationLog("新增了热门视频ID为{$res}的信息", 1);
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'contentManager/hotvideoList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }
    }



    /**
     * 编辑
     */
    public function edithotvideo($id){
        $data = DB::table('hotvideo')->where('id',$id)->first();
        return view('admin.contentManager.hotvideo.edithotvideo')->with('data',$data);
    }


    /**
     * 编辑方法
     */
    public function editshotvideo(Request $request){
        $input = Input::except('_token');
        $input['created_at'] = Carbon::now();
        //判断推荐位是否存在
        $isexit = DB::table('hotvideo')->where('sort',$request['sort'])->first();
        if($isexit){
            DB::table('hotvideo')->where('id',$isexit->id)->update(['sort'=>0]);
        }
        if($request->hasFile('cover')){ //判断文件是否存在
            if($request->file('cover')->isValid()){ //判断文件在上传过程中是否出错
                $name = $request->file('cover')->getClientOriginalName();//获取图片名
                $entension = $request->file('cover')->getClientOriginalExtension();//上传文件的后缀
                $newname = md5(date('cover'.$name)).'.'.$entension;//拼接新的图片名
                if($request->file('cover')->move('./admin/image/contentManager/hotvideo',$newname)){
                    $input['cover'] = '/admin/image/contentManager/hotvideo/'.$newname;
                }else{
                    return redirect()->back()->withInput()->withErrors('文件保存失败');
                }

            }else{
                return redirect()->back()->withInput()->withErrors('文件在上传过程中出错');
            }
        }
        //验证
        $validate = $this->validator_edit($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $res = DB::table('hotvideo')->where('id',$input['id'])->update($input);
        if($res){
            $this -> OperationLog("修改了热门视频ID为{$request['id']}的信息", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'contentManager/hotvideoList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }







    /**
     * 删除
     */
    public function delhotvideo($id){
        $res = DB::table('hotvideo')->where('id',$id)->delete();
        if($res){
            $this -> OperationLog("删除了热门视频ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'contentManager/hotvideoList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }



    /**
     * 审核状态
     */
    public function hotvideoStatus(Request $request){
        $data['status'] = $request['status'];
        $data = DB::table('hotvideo')->where('id',$request['id'])->update($data);
        if($data){
            $this -> OperationLog("修改了热门视频ID为{$request['id']}的状态", 1);
            echo 1;
        }else{
            echo 0;
        }
    }


    /**
     * 上传资源
     */
    public function dohotvideo(Request $request)
    {
        if($request->hasFile('Filedata')){ //判断文件是否存在
            if($request->file('Filedata')->isValid()){ //判断文件在上传过程中是否出错
                $name = $request->file('Filedata')->getClientOriginalName();//获取图片名
                $entension = $request->file('Filedata')->getClientOriginalExtension();//上传文件的后缀
                $newname = md5(date('ymdhis'.$name)).'.'.$entension;//拼接新的图片名
                if($request->file('Filedata')->move('./admin/hotvideo/',$newname)){
                    echo '/admin/hotvideo/'.$newname;
                }else{
                    echo '文件保存失败';
                }
            }else{
                echo '文件上传出错';
            }
        }
    }



    /**
     * 验证
     */
    protected function validator(array $data){
        $rules = [
            'coursePath' => 'required',
            'title' => 'required',
            'videoIntro' => 'required',
            'cover' => 'required'
        ];
        $messages = [
            'coursePath.required' => '请上传视频',
            'title.required' => '请填写标题',
            'videoIntro.required' => '请填写内容',
            'cover.required' => '请上传封面'
        ];

        return \Validator::make($data, $rules, $messages);
    }


    /**
     * 验证修改
     */
    protected function validator_edit(array $data){
        $rules = [
//            'coursePath' => 'required',
            'title' => 'required',
            'videoIntro' => 'required',
//            'cover' => 'required'
        ];
        $messages = [
//            'coursePath.required' => '请上传视频',
            'title.required' => '请填写标题',
            'videoIntro.required' => '请填写内容',
//            'cover.required' => '请上传封面'
        ];

        return \Validator::make($data, $rules, $messages);
    }



}
