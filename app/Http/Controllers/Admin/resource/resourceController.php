<?php

namespace App\Http\Controllers\Admin\resource;

use App\Http\Controllers\Home\lessonComment\Gadget;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class resourceController extends Controller
{
    use Gadget;
    /**
     * 资源列表
     */
    public function resourceList(Request $request){
//        dump($request->all());
        $query = DB::table('resource as r');

        if($request['beginTime']){ //上传的起止时间
            $query = $query->where('r.created_at','>=',$request['beginTime']);
        }
        if($request['endTime']){ //上传的起止时间
            $query = $query->where('r.created_at','<=',$request['endTime']);
        }

        if($request['resourceGrade']){
            $query = $query->where('resourceGrade',$request['resourceGrade']);
        }
        if($request['resourceSubject']){
            $query = $query->where('resourceSubject',$request['resourceSubject']);
        }
        if($request['resourceEdition']){
            $query = $query->where('resourceEdition',$request['resourceEdition']);
        }
        if($request['resourceBook']){
            $query = $query->where('resourceBook',$request['resourceBook']);
        }
        if($request['resourceChapter']){
            $query = $query->where('resourceChapter',$request['resourceChapter']);
        }
        if($request['resourceType']){
            $query = $query->where('resourceType',$request['resourceType']);
        }

        if($request['type'] == 1){
            $query = $query->where('r.id','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 2){
            $query = $query->where('r.resourceTitle','like','%'.trim($request['search']).'%');
        }
        if($request['type'] == 3){
            $query = $query->where('r.resourceAuthor','like','%'.trim($request['search']).'%');
        }

        $data = $query
            ->leftJoin('users as u','u.id','=','r.userId')
            ->leftJoin('resourcetype as t','t.id','=','r.resourceType')
            ->leftJoin('studysection as x','x.id','=','r.resourceSection')
            ->leftJoin('schoolgrade as g','g.id','=','r.resourceGrade')
            ->leftJoin('studysubject as s','s.id','=','r.resourceSubject')
            ->leftJoin('studyedition as e','e.id','=','r.resourceEdition')
            ->leftJoin('studyebook as b','b.id','=','r.resourceBook')
            ->leftJoin('chapter as c','c.id','=','r.resourceChapter')
            ->where('resourceIsDel',0)
            ->orderBy('id','desc')
            ->select('r.*','u.username','t.resourceTypeName','g.gradeName','s.subjectName','e.editionName','b.bookName','c.chapterName','x.sectionName')
            ->paginate();

        //取出系统中文档是否需要转码
        $isTranscode = DB::table('systemsttings')->where('type',0)->pluck('isTrue');
//        dd($isTranscode);

        $type = ['png','jpg','jpeg','pdf','swf'];
        $document = ['doc','docx','xls','xlsx','ppt','pptx'];

        foreach($data as &$val){
//            dump($val->resourceFormat);
//            $paasdownUrl = $this->getdownload( $val->fileID);
//            $fileUrl = $paasdownUrl['data'];
//            $aa = "http://182.18.34.215:7777/?Method=View&FileUrl=". $fileUrl ."&Type=1&Width=500&Height=450";
//            dd($aa);
//            $online = $this->onlinePlay($fileUrl,1,500,450);
//            dd($online);
//            dd('====');


            if(!in_array(strtolower($val->resourceFormat),$type)){
                if(!$val->courseLowPath || !$val->courseMediumPath || !$val->courseHighPath){

                    if(strtolower($val->resourceFormat) == 'mp3'){
                        $convertype = 1; //音频
                    }elseif(in_array(strtolower($val->resourceFormat),$document)){
                        $convertype = 2; //文档
                    }else{
                        $convertype = 0; //视频
                    }

                    $FileList = $this->transformations($val->fileID,$convertype);
//                    dd($FileList);

                    //转换失败
                    if($FileList['code'] == 503){
                        DB::table('resource')->where('id',$val->id)->update(['passCode'=>3]);
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
                            DB::table('resource')->where('id',$val->id)->update($lists);
                            DB::table('resource')->where('id',$val->id)->update(['passCode'=>2]);
                        }
                    }
                    if(in_array(strtolower($val->resourceFormat),$document) && $val->courseLowPath){
                        if(!$val->resourcePath && $val->courseLowPath){
                            $paasdownUrl = $this->getdownload( $val->courseLowPath);

                            if($paasdownUrl['code'] == 200){
                                $path = realpath(base_path('public')).'/PdfFile/'.$val->fileID.'.pdf';
                                try{
                                    $curl = $this->curl_file_get_contents($paasdownUrl['data'],$path);
                                }catch(\Exception $e){
                                    Log::info('admin resourceList '.$e->getMessage());
                                    abort(404);
                                }
//                                $curl = $this->curl_file_get_contents($paasdownUrl['data'],$path);
                                if($curl){
                                    $curlPath = '/PdfFile/'.$val->fileID.'.pdf';
                                    DB::table('resource')->where('id',$val->id)->update(['resourcePath'=>$curlPath]);
                                }
                            }
                        }
                    }



                }

            }else{
                //直接下载
                if(!$val->resourcePath && $val->fileID){
                    $paasdownUrl = $this->getdownload( $val->fileID);
                    if($paasdownUrl['code'] == 200){
                        $path = realpath(base_path('public')).'/PdfFile/'.$val->fileID.'.'.strtolower($val->resourceFormat);
                        $curl = $this->curl_file_get_contents($paasdownUrl['data'],$path);
                        if($curl){
                            $curlPath = '/PdfFile/'.$val->fileID.'.'.strtolower($val->resourceFormat);
                            DB::table('resource')->where('id',$val->id)->update(['resourcePath'=>$curlPath]);
                            DB::table('resource')->where('id',$val->id)->update(['passCode'=>2]);
                        }
                    }
                }
            }
        }


        $data->type = $request['type'];
        $data->beginTime = $request['beginTime'];
        $data->endTime = $request['endTime'];
        $data->resourceGrade = $request['resourceGrade'] ? $request['resourceGrade'] : 0;
        $data->resourceSubject = $request['resourceSubject'] ? $request['resourceSubject'] : 0;
        $data->resourceEdition = $request['resourceEdition'] ? $request['resourceEdition'] : 0;
        $data->resourceBook = $request['resourceBook'] ? $request['resourceBook'] : 0;
        $data->resourceType = $request['resourceType'] ? $request['resourceType'] : 0;
        $data->isTranscode = $isTranscode;
//        dd($data);
        return view('admin.resource.resourceList',['data'=>$data]);
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
     *更改资源状态
     */
    public function status($id,$statusId){
        $data = DB::table('resource')->where('id',$id)->update(['resourceStatus'=>$statusId]);
        return back();
    }

    /**
     *编辑资源
     */
    public function editResource($id){
        $data = DB::table('resource as r')
            ->leftJoin('resourcetype as t','t.id','=','r.resourceType')
            ->leftJoin('studysection as x','x.id','=','r.resourceSection')
            ->leftJoin('schoolgrade as g','g.id','=','r.resourceGrade')
            ->leftJoin('studysubject as s','s.id','=','r.resourceSubject')
            ->leftJoin('studyedition as e','e.id','=','r.resourceEdition')
            ->leftJoin('studyebook as b','b.id','=','r.resourceBook')
            ->leftJoin('chapter as c','c.id','=','r.resourceChapter')
            ->where('r.id',$id)
            ->select('r.*','t.resourceTypeName','g.gradeName','s.subjectName','e.editionName','b.bookName','c.chapterName','x.sectionName')
            ->first();
//        dd($data);
        return view('admin.resource.editResource',['data'=>$data]);
    }

    /**
     *执行编辑
     */
    public function doEditResource(Request $request){
        $data = $request->except('_token');
        $data['updated_at'] = Carbon::now();
        if(DB::table('resource')->where('id',$request['id'])->update($data)){
            $this -> OperationLog('修改了id为'.$request['id'].'资源');
            return redirect('admin/message')->with(['status'=>'修改成功','redirect'=>'resource/resourceList']);
        }else{
            return redirect()->back()->withInput()->withErrors('修改失败');
        }
    }

    /**
     *获取年级
     */
    public function getGrade(){
        $data = DB::table('schoolgrade')->where('parentId',1)->select('id','gradeName')->get();
        return response()->json($data);
    }

    /**
     *获取科目
     */
    public function getSubject(){
        $data = DB::table('studysubject')->select('id','subjectName')->get();
        return response()->json($data);
    }

    /**
     *获取版本
     */
    public function getEdition(){
        $data = DB::table('studyedition')->select('id','editionName')->get();
        return response()->json($data);
    }

    /**
     * 获取册别
     */
    public function getBook(){
        $data = DB::table('studyebook')->select('id','bookName')->get();
        return response()->json($data);
    }

    /**
     *获取知识点
     */
    public function getChapter($gradeId=null,$subjectId=null,$editionId=null,$bookId=null){
        $query = DB::table('chapter');

        if($gradeId){
            $query = $query->where('gradeId',$gradeId);
        }
        if($subjectId){
            $query = $query->where('subjectId',$subjectId);
        }
        if($editionId){
            $query = $query->where('editionId',$editionId);
        }
        if($bookId){
            $query = $query->where('bookId',$bookId);
        }

        $data = $query
            ->where('sectionId',1)
            ->select('id','chapterName')
            ->get();
        return response()->json($data);
    }

    /**
     *获取资源类型
     */
    public function getResourceType(){
        $data = DB::table('resourcetype')->select('id','resourceTypeName')->get();
        return response()->json($data);
    }

    /**
     *删除资源
     */
    public function delResource($id){
        $data = DB::table('resource')->where('id',$id)->update(['resourceIsDel'=>1]);
        if($data){
            $this -> OperationLog('删除了id为'.$id.'的资源');
            return redirect()->back()->with(['status'=>'删除成功']);
        }else{
            return redirect()->back()->withErrors('删除失败');
        }
    }

    /**
     * @param $request
     * @return resource
     * duo删除资源
     */
    public function delMultiResource(Request $request){
        $rules = ['check' => 'required',];
        $messages = ['check.required' => '请选择删除项'];
        $validate = \Validator::make($request->all(),$rules,$messages);
        if($validate->fails()){
            return \Redirect::back()->withErrors($validate);
        }
        $data = DB::table('resource') -> whereIn('id', $request['check']) -> update(['resourceIsDel'=>1]);
        if($data){
            foreach  ($request['check'] as $id) {
                $this -> OperationLog('删除了id为'.$id.'的资源');
            }
            return redirect('admin/message')->with(['status'=>'资源删除成功','redirect'=>'resource/resourceList']);
        }else{
            return redirect('admin/message')->with(['status'=>'资源删除失败','redirect'=>'resource/resourceList']);
        }
    }


    // 上传头像接口
    public function addImg(Request $request)
    {
        //获取文件后缀名
        $ext = strrchr($_FILES['Filedata']['name'], '.');

        if ($request->hasFile('Filedata')) {
            if ($request->file('Filedata')->isValid()) {
                $newname = time() . $ext;
                if ($request->file('Filedata')->move('./uploads/cut/', $newname)) {

                    $a = $this->suofang('/uploads/cut/' . $newname, $request['width'], $request['height']);
                    $filename = substr($a, strrpos($a, '/') + 1);
                    $arr = [
                        "src" => $a,
                        "width" => getimagesize(realpath(base_path('public')) . $a)[0],
                        "height" => getimagesize(realpath(base_path('public')) . $a)[1],
                        'name' => $filename,
                    ];

                    if (file_exists(realpath(base_path('public')) . '/uploads/cut/' . $newname)) {
                        unlink(realpath(base_path('public')) . '/uploads/cut/' . $newname);
                    }

                    return response()->json($arr);

                }
            }
        } else {
            echo 0;  //没有文件上传
        }
    }


    /*
     * @param $path 图片url
     * @param $width 目标图宽
     * @param $height 目标图高
     */
    function suofang($path, $width, $height)
    {
        $name = '/uploads/cut/suofang' . time() . ".png";
        $src = $this->getimagetype($path);
        if ($src['width'] < $width && $src['height'] < $height) {
            copy(realpath(base_path('public')) . $path, realpath(base_path('public')) . $name);
            return $name;
        }

        if ($src['width'] > $src['height']) {
            $height = $src['height'] * ($width / $src['width']);
        } else {
            $width = $src['width'] * ($height / $src['height']);
        }

        $des = imagecreatetruecolor($width, $height);

        imagecopyresampled($des, $src['res'], 0, 0, 0, 0, $width, $height, $src['width'], $src['height']);

        imagepng($des, realpath(base_path('public')) . $name);


        imagedestroy($src['res']);
        imagedestroy($des);

        return $name;

    }


    public function trimImg()
    {
        $headImgSrc = $this->cut($_POST['imgsrc'], $_POST['x'], $_POST['y'], $_POST['w'], $_POST['h'],$_POST['name'],$_POST['width'],$_POST['height']);
        if (file_exists(realpath(base_path('public')) . $_POST['imgsrc'])) {
            unlink(realpath(base_path('public')) . $_POST['imgsrc']);
        }
        return Response()->json($headImgSrc);
    }

    /*
     * @param $path 图片url
     * @param $x 原图x坐标
     * @param $y 原图y坐标
     * @param $w 原图宽
     * @param $h 原图高
     */
    function cut($path, $x, $y, $w, $h,$name,$width,$height)
    {
        $name = substr($name,7);
        $name = '/uploads/cut/cut' . $name;
        $src = $this->getimagetype($path);

        $des = imagecreatetruecolor($width, $height);

        imagecopyresampled($des, $src['res'], 0, 0, $x, $y, $width, $height, $w, $h);

        imagepng($des, realpath(base_path('public')) . $name);


        imagedestroy($src['res']);
        imagedestroy($des);

        return $name;

    }

    function getimagetype($path)
    {
        $path = realpath(base_path('public')) . $path;
        $imgarr = getimagesize($path);
        switch ($imgarr['mime']) {
            case 'image/jpeg':
            case 'image/jpg':
            case 'image/pjpeg':
                $img = imagecreatefromjpeg($path);
                break;
            case 'image/png':
                $img = imagecreatefrompng($path);
                break;
            case 'image/gif':
                $img = imagecreatefromgif($path);
                break;
        }
        $info['res'] = $img;
        $info['width'] = $imgarr[0];
        $info['height'] = $imgarr[1];
        return $info;
    }

}
