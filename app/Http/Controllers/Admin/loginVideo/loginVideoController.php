<?php

namespace App\Http\Controllers\Admin\loginVideo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class loginVideoController extends Controller
{
    /**
     * 登录视频列表
     */
    public function loginVideoList(){
        $data = DB::table('loginvideo')->orderBy('id','desc')->paginate(15);
        return view('admin/loginvideo/loginVideosList',['data'=>$data]);
    }

    /**
     *添加登录视频
     */
    public function addLoginVideo(){
        return view('admin.loginvideo.addLoginVideo');
    }
    public function doAddLoginVideo(Request $request){
        if(!$request['video']){
            return redirect()->back()->withInput()->withErrors('请上传视频');
        }
        $data = $request->except('_token');
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        if($request->hasFile('pic')){ //判断文件是否存在
            if($request->file('pic')->isValid()){ //判断文件在上传过程中是否出错
                $name = $request->file('pic')->getClientOriginalName();//获取图片名
                $entension = $request->file('pic')->getClientOriginalExtension();//上传文件的后缀
                $newname = md5(date('ymdhis'.$name)).'.'.$entension;//拼接新的图片名
                if($request->file('pic')->move('./home/image/lessonSubject',$newname)){
                    $data['pic'] = '/home/image/lessonSubject/'.$newname;
                }else{
                    return redirect()->back()->withInput()->withErrors('文件保存失败');
                }

            }else{
                return redirect()->back()->withInput()->withErrors('文件在上传过程中出错');
            }
        }else{
            return redirect()->back()->withInput()->withErrors('请上传封面图');
        }
        if($id = DB::table('loginvideo')->insertGetId($data)){
            $this -> OperationLog('添加了id为'.$id.'的登录视频推荐');
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'loginVideo/loginVideoList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    /**
     *上传图片
     */
    public function doUploadfile(Request $request){
        if($request->hasFile('Filedata')){ //判断文件是否存在
            $entension = $request->file('Filedata')->getClientOriginalExtension();//上传文件的后缀
            if($entension == 'mp4'){
                if($request->file('Filedata')->isValid()){ //判断文件在上传过程中是否出错
                    $name = $request->file('Filedata')->getClientOriginalName();//获取图片名
                    $newname = md5(date('ymdhis'.$name)).'.'.$entension;//拼接新的图片
                    if($request->file('Filedata')->move(realpath(base_path('public')).'/uploads/heads/',$newname)){
                        echo '/uploads/heads/'.$newname;
                    }else{
                        echo '文件保存失败';
                    }
                }else{
                    echo '文件上传出错';
                }
            }else{
                echo '文件格式不正确';
            }

        }
    }

    /**
     * 修改
     */
    public function editLoginVideo($id){
        $data = DB::table('loginvideo')->where('id',$id)->first();
        return view('admin.loginvideo.editLoginVideo',['data'=>$data]);
    }
    public function doEditLoginVideo(Request $request){
        $data = $request->except('_token');
        $data['updated_at'] = Carbon::now();
        if($request->hasFile('pic')){ //判断文件是否存在
            if($request->file('pic')->isValid()){ //判断文件在上传过程中是否出错
                $name = $request->file('pic')->getClientOriginalName();//获取图片名
                $entension = $request->file('pic')->getClientOriginalExtension();//上传文件的后缀
                $newname = md5(date('ymdhis'.$name)).'.'.$entension;//拼接新的图片名
                if($request->file('pic')->move('./home/image/lessonSubject',$newname)){
                    $data['pic'] = '/home/image/lessonSubject/'.$newname;
                }else{
                    return redirect()->back()->withInput()->withErrors('文件保存失败');
                }

            }else{
                return redirect()->back()->withInput()->withErrors('文件在上传过程中出错');
            }
        }
        if(DB::table('loginvideo')->where('id',$request['id'])->update($data)){
            $this -> OperationLog('修改了id为'.$request['id'].'的登录视频');
            return redirect('admin/message')->with(['status'=>'修改成功','redirect'=>'loginVideo/loginVideoList']);
        }else{
            return redirect()->back()->withInput()->withErrors('修改失败');
        }
    }

    /**
     *删除
     */
    public function delLoginVideo($id){
        if(DB::table('loginvideo')->where('id',$id)->delete()){
            $this -> OperationLog('删除了id为'.$id.'的登录视频');
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'loginVideo/loginVideoList']);
        }else{
            return redirect('admin/message')->with(['status'=>'删除失败','redirect'=>'loginVideo/loginVideoList']);
        }
    }

}
