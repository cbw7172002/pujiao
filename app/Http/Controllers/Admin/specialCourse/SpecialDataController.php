<?php

namespace App\Http\Controllers\Admin\specialCourse;

use App\Http\Controllers\Home\lessonComment\Gadget;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use PaasResource;
use PaasUser;

class SpecialDataController extends Controller
{
    use Gadget;
    /**
     *资料列表
     */
    public function dataList(Request $request,$id){
        $query = DB::table('coursedata');
        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('created_at','<=',$request['endTime']);
        }

        if($request['type'] == 1){
            $query = $query->where('id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('dataName','like','%'.trim($request['search']).'%');
        }

        $data = $query
            ->where('courseid',$id)
            ->orderBy('id','desc')
            ->paginate(15);
        $data->type = $request['type'];
        $data->courseId = $id;
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
        foreach($data as &$val){
            //文件后缀
            $type = strtolower(substr($val->dataName,strpos($val->dataName,'.')+1));
            $val->type = $type;
            if(!$val->courseLowPath && $type != 'pdf'){
                if($val->fileID){
                    $FileList = $this->transformations($val->fileID,2);
//                    dd($FileList);

                    //转换失败
                    if($FileList['code'] == 503){
                        DB::table('course')->where('id',$val->courseId)->update(['courseStatus'=>6]);
                        DB::table('coursedata')->where('id',$val->id)->update(['isSuccess'=>1]);
                    }

                    //返回的状态值
                    $val->msg['message'] = $FileList['message'];
                    $val->msg['code'] = $FileList['code'];

                    if($FileList['code'] == 200 && $FileList['data']['Waiting'] < 0){
                        $filelists = $FileList['data']['FileList']; //取出转好的码
                        $lists = [];
                        foreach($filelists as $value){
                            switch($value['Level']){
                                case 0:
                                    $lists['courseLowPath'] = $value['FileID'];
                                    break;
                                case 1:
                                    $lists['courseLowPath'] = $value['FileID'];
                                    break;
                                case 2:
                                    $lists['courseMediumPath'] = $value['FileID'];
                                    break;
                                case 3:
                                    $lists['courseMediumPath'] = $value['FileID'];
                                    break;
                            }
                        }
                        if($lists){
                            DB::table('coursedata')->where('id',$val->id)->update($lists);
                            DB::table('course')->where('id',$val->courseId)->update(['courseStatus'=>1]);
                            DB::table('coursedata')->where('id',$val->id)->update(['isSuccess'=>0]);
                        }
                    }
                }
            }else{
                if($type == 'pdf'){
                    $paasdownUrl = $this->getdownload( $val->fileID);
                }else{
                    $paasdownUrl = $this->getdownload( $val->courseLowPath);
                }
//                    dd($paasdownUrl);
                if(!$val->dataPath){
                    if($paasdownUrl['code'] == 200){
                        $path = realpath(base_path('public')).'/PdfFile/'.$val->fileID.'.pdf';
                        $curl = $this->curl_file_get_contents($paasdownUrl['data'],$path);
                        if($curl){
                            $curlPath = '/PdfFile/'.$val->fileID.'.pdf';
                            DB::table('coursedata')->where('id',$val->id)->update(['dataPath'=>$curlPath]);
                        }
                    }
                }
            }
        }
//        dd($data);
        return view('admin/specialCourse/specialDataList',['data'=>$data]);
    }

    function curl_file_get_contents($durl,$path){
        ob_start();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $durl);
        curl_setopt($ch, CURLOPT_HEADER, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
//        $path = $path;
//        $path = $path.".pdf";
        $arrdata=fopen($path,"a");
        fwrite($arrdata,$r);
        fclose($arrdata);
        curl_close($ch);
        return $path;

    }

    /**
     *课程资料状态
     */
    public function courseDataState(Request $request){
        $data['status'] = $request['status'];
        $data['updated_at'] = Carbon::now();
        $data = DB::table('coursedata')->where('id',$request['id'])->update($data);
        if($data){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     *添加资料
     */
    public function addData($id){
        return view('admin/specialCourse/addData',['courseid'=>$id]);
    }

    /**
     *执行添加资料
     */
    public function doAddData(Request $request){
//        dd($request->all());
//        $data = $request->except('_token');
        $data['courseId'] = $request['courseId'];
        $data['dataName'] = $request['dataName'];
        $data['fileID'] = $request['fileID'];
        $data['size'] = $request['size'];
        if($request['courseLowPath'] != 'null'){
            $data['courseLowPath'] = $request['courseLowPath'];
            //说明转码成功需修改corse表状态值
            DB::table('course')->where('id',$request['courseId'])->update(['courseStatus'=>1]);
        }
        if($request['courseMediumPath'] != 'null'){
            $data['courseMediumPath'] = $request['courseMediumPath'];
        }
        if($request['courseHighPath'] != 'null'){
            $data['courseHighPath'] = $request['courseHighPath'];
        }
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
//        dd($data);
        if($id = DB::table('coursedata')->insertGetId($data)){
            $this -> OperationLog('添加了id为'.$id.'的课程资料');
//            return redirect('admin/message')->with(['status'=>'课程资料添加成功','redirect'=>'specialCourse/dataList/'.$request['courseid']]);
            echo '1';
        }else{
//            return redirect()->back()->withInput()->withErrors('课程资料添加失败');
            echo '0';
        }
    }

    /**
     * @param Request $request
     */
    public function doUploadfile(Request $request)
    {
        if($request->hasFile('Filedata')){ //判断文件是否存在
            $entension = $request->file('Filedata')->getClientOriginalExtension();//上传文件的后缀
            if($entension == 'mp4' || $entension == 'fiv' || $entension == 'avi' || $entension == 'rmvb' || $entension == 'wmv' || $entension == 'mkv'){
                echo '文件格式不正确';
            }else{
                if($request->file('Filedata')->isValid()){ //判断文件在上传过程中是否出错
                    $name = $request->file('Filedata')->getClientOriginalName();//获取图片名
                    $newname = md5(date('ymdhis'.$name)).'.'.$entension;//拼接新的图片名
                    if($request->file('Filedata')->move('./uploads/heads/',$newname)){
                        echo '/uploads/heads/'.$newname;
                    }else{
                        echo '文件保存失败';
                    }
                }else{
                    echo '文件上传出错';
                }
            }

        }
    }

    /**
     *编辑课程资料
     */
    public function editData($id){
        $data = DB::table('coursedata')->where('id',$id)->first();
        return view('admin/specialCourse/editData',['data'=>$data]);
    }

    /**
     *执行编辑
     */
    public function doEditData(Request $request){
//        dd($request->all());
//        $data = $request->except('_token','organurl');
        $data['dataName'] = $request['dataName'];
        $data['fileID'] = $request['fileID'];
        $data['size'] = $request['size'];
//        if($request['courseLowPath'] != 'null'){
            $data['courseLowPath'] = $request['courseLowPath'];
            //说明转码成功需修改corse表状态值
            DB::table('course')->where('id',$request['courseId'])->update(['courseStatus'=>1]);
            DB::table('coursedata')->where('id',$request['id'])->update(['isSuccess'=>0]);
//        }
        if($request['courseMediumPath'] != 'null'){
            $data['courseMediumPath'] = $request['courseMediumPath'];
        }
        if($request['courseHighPath'] != 'null'){
            $data['courseHighPath'] = $request['courseHighPath'];
        }
        $data['dataPath'] = '';
        $data['updated_at'] = Carbon::now();
        if(DB::table('coursedata')->where('id',$request['id'])->update($data)){
            $this -> OperationLog('修改了id为'.$request['id'].'的课程资料');
            echo '1';
        }else{
            echo '0';
        }
    }

    /**
     *删除课程资料
     */
    public function delData($courseid,$id){
        if(DB::table('coursedata')->where('id',$id)->delete()){
            $this -> OperationLog('删除了id为'.$id.'的课程资料');
            return redirect('admin/message')->with(['status'=>'课程章节删除成功','redirect'=>'specialCourse/dataList/'.$courseid]);
        }else{
            return redirect('admin/message')->with(['status'=>'课程章节删除失败','redirect'=>'specialCourse/dataList/'.$courseid]);
        }
    }
}
