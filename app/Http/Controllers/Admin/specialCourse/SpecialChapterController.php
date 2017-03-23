<?php

namespace App\Http\Controllers\Admin\specialCourse;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Home\lessonComment\Gadget;
use PaasResource;
use PaasUser;
use Cache;

class SpecialChapterController extends Controller
{
    use Gadget;
    /**
     *课程章节列表
     */
    public function specialChapterList(Request $request,$id){
        $query = DB::table('coursechapter as ch');

        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('ch.created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('ch.created_at','<=',$request['endTime']);
        }

        if($request['type'] == 1){
            $query = $query->where('ch.id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('c.courseTitle','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('ch.title','like','%'.trim($request['search']).'%');
        }

        $data = $query
            ->leftJoin('course as c','ch.courseId','=','c.id')
            ->select('ch.*','c.courseTitle')
            ->where('ch.courseId',$id)
            ->orderBy('ch.id','asc')
            ->paginate(15);

        //取出系统中文档是否需要转码
        $isTranscode = DB::table('systemsttings')->where('type',0)->pluck('isTrue');
//        dd($isTranscode);

//        dd($data);
        $type = ['png','jpg','jpeg','pdf','swf'];
        $document = ['doc','docx','xls','xlsx','ppt','pptx'];

        foreach($data as &$val){
            if($val->courseType === null){
//                dump($val->id.'==='.$val->courseType);
                $coursetype = DB::table('coursechapter')->where('id',$val->parentId)->select('parentId','courseType')->first();
                if($coursetype->courseType == null){
                    $val->courseType = DB::table('coursechapter')->where('id',$coursetype->parentId)->pluck('courseType');
                }else{
                    $val->courseType = $coursetype->courseType;
                }
            }
            if($val->parentId != 0){ //表示是节
                if($val->courseLowPath){
                    if(!Cache::get($val->courseLowPath)){
                        $val->courseLowPathurl = $this->getPlayUrl($val->courseLowPath);
                        Cache::put($val->courseLowPath,$val->courseLowPathurl,1800);
                    }else{
                        $val->courseLowPathurl = Cache::get($val->courseLowPath);
                    }
                }else{
                    $val->courseLowPathurl = null;
                }
            }

            if(!in_array(strtolower($val->courseFormat),$type)){
                if(!$val->courseLowPath || !$val->courseMediumPath || !$val->courseHighPath){
                    if($val->fileID){

                        if(strtolower($val->courseFormat) == 'mp3'){
                            $convertype = 1; //音频
                        }elseif(in_array(strtolower($val->courseFormat),$document)){
                            $convertype = 2; //文档
                        }else{
                            $convertype = 0; //视频
                        }

                        $FileList = $this->transformations($val->fileID,$convertype);
//                        dump($FileList);

                        //转换失败
                        if($FileList['code'] == 503){
                            DB::table('course')->where('id',$val->courseId)->update(['courseStatus'=>6]);
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
                                        $lists['courseHighPath'] = $value['FileID'];
                                        break;
                                }
                            }
                            if($lists){
                                DB::table('coursechapter')->where('id',$val->id)->update($lists);
                                DB::table('coursechapter')->where('id',$val->id)->update(['isSuccess'=>0]);
                            }
                        }

                        if(in_array(strtolower($val->courseFormat),$document) && $val->courseLowPath){
                            if(!$val->coursePath){
                                $paasdownUrl = $this->getdownload( $val->courseLowPath);

                                if($paasdownUrl['code'] == 200){
                                    $path = realpath(base_path('public')).'/PdfFile/'.$val->fileID.'.pdf';
                                    $curl = $this->curl_file_get_contents($paasdownUrl['data'],$path);
                                    if($curl){
                                        $curlPath = '/PdfFile/'.$val->fileID.'.pdf';
                                        DB::table('coursechapter')->where('id',$val->id)->update(['coursePath'=>$curlPath]);
                                    }
                                }
                            }
                        }
                    }
                }

            }else{
                //直接下载
                if(!$val->coursePath && $val->fileID){
                    $paasdownUrl = $this->getdownload( $val->fileID);
//                    $val->msg['message'] = '正在转码...';
//                    $val->msg['code'] = 600;
                    if($paasdownUrl['code'] == 200){
                        $path = realpath(base_path('public')).'/PdfFile/'.$val->fileID.'.'.strtolower($val->courseFormat);
                        $curl = $this->curl_file_get_contents($paasdownUrl['data'],$path);
                        if($curl){
                            $curlPath = '/PdfFile/'.$val->fileID.'.'.strtolower($val->courseFormat);
                            DB::table('coursechapter')->where('id',$val->id)->update(['coursePath'=>$curlPath]);
                        }
                    }
                }
            }

        }
        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
        $data->courseId = $id;
        $data->isTranscode = $isTranscode;
//        dd($data);
        return view('admin/specialCourse/specialChapterList',['data'=>$data]);
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
     *课程章节状态
     */
    public function specialChapterState(Request $request){
        $data['status'] = $request['status'];
        $data['updated_at'] = Carbon::now();
        $data = DB::table('coursechapter')->where('id',$request['id'])->update($data);
        if($data){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     *添加课程章节
     */
    public function addSpecialChapter($id){
        $data = [];
        $data['data'] = DB::table('coursechapter')->where('courseId',$id)->where('parentId',0)->get();
        if(!$data){
            $data['state'] = 0;
        }else{
            $data['state'] = 1;
        }
//        dd($data);
        return view('admin/specialCourse/addSpecialChapter',['courseid'=>$id,'data'=>$data]);
    }

    /**
     *执行添加课程章节
     */
    public function doAddSpecialChapter(Request $request){
//        dd($request->all());exit();
        if($request['selectid'] == 1){ //表示章
            $data['courseId'] = $request['courseid'];
            $data['title'] = $request['title'];
            $data['parentId'] = 0;

        }
        if($request['selectid'] == 2){ //节
            $data['courseId'] = $request['courseid'];
            $data['title'] = $request['title'];
            $data['parentId'] = $request['parentId'];
            $data['isTrylearn'] = $request['isTrylearn'];
            $data['courseFormat'] = $request['courseFormat'];
            $data['fileID'] = $request['fileID'];

        }
        $data['created_at'] = date('Y-m-d H:i:s',time());
        $data['updated_at'] = date('Y-m-d H:i:s',time());

        if($id = DB::table('coursechapter')->insertGetId($data)){
            $this -> OperationLog('添加了id为'.$id.'的课程章节');
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     *编辑课程章节
     */
    public function editSpecialChapter($courseid,$id){
        $data = DB::table('coursechapter')->where('id',$id)->first();
        $chapters = DB::table('coursechapter')->where('courseId',$courseid)->where('parentId',0)->get();
        return view('admin/specialCourse/editSpecialChapter',['data'=>$data,'chapters'=>$chapters]);
    }

    /**
     *执行课程章节编辑
     */
    public function doEditSpecialChapter(Request $request){
//        dd($request->all());exit();
        if($request['selectid'] == 1){  //章
            $data['title'] = $request['title'];
        }
        if($request['selectid'] == 2){
            $data['title'] = $request['title'];
            $data['isTrylearn'] = $request['isTrylearn'];
            $data['courseFormat'] = $request['courseFormat'];
            $data['fileID'] = $request['fileID'];
        }
        $data['updated_at'] = Carbon::now();
        if(DB::table('coursechapter')->where('id',$request['id'])->update($data)){
            $this -> OperationLog('修改了id为'.$request['id'].'的课程章节');
            echo 1;
        }else{
            echo 0;
        }
    }


    /**
     *删除章节
     */
    public function delSpecialChapter($courseid,$id){
        if(DB::table('coursechapter')->where('id',$id)->delete()){
            $this -> OperationLog('删除了id为'.$id.'的课程章节');
            return redirect('admin/message')->with(['status'=>'课程章节删除成功','redirect'=>'specialCourse/specialChapterList/'.$courseid]);
        }else{
            return redirect('admin/message')->with(['status'=>'课程章节删除失败','redirect'=>'specialCourse/specialChapterList/'.$courseid]);
        }
    }

    /**
     * 表单验证
     */
    protected function validator(array $data)
    {
        $rules = [
            'parentId' => 'required',
            'title' => 'required',
            'password' => 'sometimes|required|min:6|max:16',
        ];

        $messages = [
            'parentId.required' => '请选择所属章(如没有请先添加)',
            'title.required' => '请输入标题',
        ];

        return \Validator::make($data, $rules, $messages);
    }
}
