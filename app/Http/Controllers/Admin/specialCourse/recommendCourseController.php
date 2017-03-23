<?php

namespace App\Http\Controllers\Admin\specialCourse;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class recommendCourseController extends Controller
{
    /**
     *专题课程推荐列表
     */
    public function recommendSpecialCourseList(){
        $query = DB::table('hotcourse as h');
        $data = $query->orderBy('id','desc')->paginate(15);
        return view('admin/specialCourse/recommend/recommendList',['data'=>$data]);
    }

    /**
     *添加专题课程推荐
     */
    public function addRecommendSpecialCourse($id){
        $data = DB::table('course')->where('id',$id)->first();
        return view('admin/specialCourse/recommend/addRecommend',['data'=>$data]);
    }

    /**
     *执行专题课程添加
     */
    public function doAddRecommendSpecialCourse(Request $request){
        $data = $request->except('_token');
        if(DB::table('hotcourse')->where('courseId',$request['courseId'])->first()){
            return redirect()->back()->withInput()->withErrors('课程不可重复推荐');
        }
        //判断推荐位是否存在
        $isexit = DB::table('hotcourse')->where('sort',$request['sort'])->first();
        if($isexit){
            DB::table('hotcourse')->where('id',$isexit->id)->update(['sort'=>0]);
        }

        $courseTitle = DB::table('course')->where('id',$request['courseId'])->select('id','courseTitle')->first();
        $data['courseName'] = $courseTitle->courseTitle;
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        if($id = DB::table('hotcourse')->insertGetId($data)){
            $this -> OperationLog('添加了id为'.$id.'的创课课程推荐');
            return redirect('admin/message')->with(['status'=>'课程推荐成功','redirect'=>'specialCourse/specialCourseList']);
        }else{
            return redirect('admin/message')->with(['status'=>'课程推荐失败','redirect'=>'specialCourse/specialCourseList']);
        }
    }

    /**
     *编辑专题课程推荐
     */
    public function editRecommendSpecialCourse($id){
        $data = DB::table('hotcourse')->where('id',$id)->first();
        return view('admin/specialCourse/recommend/editRecommend',['data'=>$data]);
    }

    /**
     *执行编辑
     */
    public function doEditRecommendSpecialCourse(Request $request){
        $data = $request->except('_token');
        //判断推荐位是否存在
        $isexit = DB::table('hotcourse')->where('sort',$request['sort'])->first();
        if($isexit){
            DB::table('hotcourse')->where('id',$isexit->id)->update(['sort'=>0]);
        }
        $data['updated_at'] = Carbon::now();
        if(DB::table('hotcourse')->where('id',$request['id'])->update($data)){
            $this -> OperationLog('修改了id为'.$request['id'].'的创课课程推荐');
            return redirect('admin/message')->with(['status'=>'课程推荐修改成功','redirect'=>'specialCourse/recommendSpecialCourseList']);
        }else{
            return redirect('admin/message')->with(['status'=>'课程推荐修改失败','redirect'=>'specialCourse/recommendSpecialCourseList']);
        }
    }

    /**
     *删除专题课程推荐
     */
    public function delRecommendSpecialCourse($id){
        if(DB::table('hotcourse')->where('id',$id)->delete()){
            $this -> OperationLog('删除了id为'.$id.'的创课课程推荐');
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'specialCourse/recommendSpecialCourseList']);
        }else{
            return redirect('admin/message')->with(['status'=>'删除失败','redirect'=>'specialCourse/recommendSpecialCourseList']);
        }
    }
}
