<?php

namespace App\Http\Controllers\Admin\resource;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class resourceTypeController extends Controller
{
    /**
     *资源类型列表
     */
    public function resourceTypeList(){
        $data = DB::table('resourcetype')->orderBy('id','desc')->paginate(15);
//        dd($data);
        return view('admin.resource.type.typeList',['data'=>$data]);
    }

    /**
     *添加资源类型
     */
    public function addResourceType(){
        return view('admin.resource.type.addType');
    }

    /**
     *执行添加
     */
    public function doAddResourceType(Request $request){
        $data = $request->except('_token');
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
//        dd($data);
        if(DB::table('resourcetype')->insert($data)){
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'resource/resourceTypeList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    /**
     *修改资源类型
     */
    public function editResourceType($id){
        $data = DB::table('resourcetype')->where('id',$id)->first();
//        dd($data);
        return view('admin.resource.type.editType',['data'=>$data]);
    }

    /**
     *执行修改
     */
    public function doEditResourceType(Request $request){
        $data = $request->except('_token');
        $data['updated_at'] = Carbon::now();
        if(DB::table('resourcetype')->where('id',$request['id'])->update($data)){
            return redirect('admin/message')->with(['status'=>'修改成功','redirect'=>'resource/resourceTypeList']);
        }else{
            return redirect()->back()->withInput()->withErrors('修改失败');
        }
    }

    /**
     *删除资源类型
     */
    public function delResourceType($id){
        if(DB::table('resourcetype')->where('id',$id)->delete()){
            return redirect()->back()->with(['status'=>'删除成功']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败');
        }
    }
}
