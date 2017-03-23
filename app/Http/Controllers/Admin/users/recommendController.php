<?php

namespace App\Http\Controllers\admin\users;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class recommendController extends Controller
{
    /**
     * 首页推荐
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function recommendHomePageList()
    {
        $data = DB::table('hotteacher')->orderBy('sort','asc')->paginate(15);
        return view('admin.users.recommend.recommendHomePageList',['data'=>$data]);
    }


    /**
     * 社区推荐
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function recommendCommunityList()
    {
        $data = DB::table('recteacher')->orderBy('sort','asc')->paginate(15);
        return view('admin.users.recommend.recommendCommunityList',['data'=>$data]);
    }
    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function addRecommendHomePage()
    {
        $data = DB::table('users')->select('id','realname')->where(['type' => 2 , 'checks' => 0])->get();
        return view('admin.users.recommend.addRecommendHomePage',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function doAddRecommendHomePage(Request $request)
    {
        $data = $request->except('_token');
        if(DB::table('hotteacher')->where('teacherId',$request['teacherId'])->first()){
            return redirect()->back()->withInput()->withErrors('名师不可重复推荐');
        }

        //判断推荐位是否存在
        $isExit = DB::table('hotteacher')->where('sort',$request['sort'])->first();
        if($isExit){
            DB::table('hotteacher')->where('id',$isExit->id)->update(['sort'=>0]);
        }


        $data['teacher'] = DB::table('users')->where('id',$request['teacherId'])->pluck('realname');
        $data['created_at'] = $data['updated_at'] = Carbon::now();
        if(DB::table('hotteacher')->insert($data)){
//            $this -> OperationLog('添加了id为'.$request['teacherId'].'的名师推荐');
            return Redirect()->to('admin/users/recommendHomePageList')->with('status','首页名师推荐成功');
        }else{
            return Redirect()->back()->withInput()->withErrors('errors','首页名师推荐失败');
        }
    }


    /**
     * Show the form for editing the specified resource.
     * 编辑
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editRecommendHomePage($id)
    {
        $data = DB::table('hotteacher')->select('id','teacher','teacherId','sort')->where('id',$id)->first();
        return view('admin.users.recommend.editRecommendHomePage',['data'=>$data]);
    }


    /**
     * 执行编辑
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function doEditRecommendHomePage(Request $request){
        $data = $request->except('_token');
        //判断推荐位是否存在
        $isExit = DB::table('hotteacher')->where('sort',$request['sort'])->first();
        if($isExit){
            DB::table('hotteacher')->where('id',$isExit->id)->update(['sort'=>0]);
        }
        $data['updated_at'] = Carbon::now();
        if(DB::table('hotteacher')->where('id',$request['id'])->update($data)){
//            $this -> OperationLog('修改了id为'.$request['id'].'的名师推荐');
            return Redirect()->to('admin/users/recommendHomePageList')->with('status','修改首页推荐成功');
        }else{
            return Redirect()->back()->withInput()->withErrors('errors','修改失败');
        }
    }


    /**
     * @param $id
     * 删除首页名师推荐
     */
    public function delRecommendHomePage($id){
        if(DB::table('hotteacher')->where('id',$id)->delete()){
//            $this -> OperationLog('删除了id为'.$id.'的名师推荐');
            return Redirect()->to('admin/users/recommendHomePageList')->with('status','删除成功');
        }else{
            return Redirect()->back()->withInput()->withErrors('errors','删除失败');
        }
    }

    /**
     * 添加社区推荐
     */
    public function addRecommendCommunity(){
        $teacher = DB::table('users')->select('id','realname')->where(['type' => 2 , 'checks' => 0])->get();
        return view('admin.users.recommend.addRecommendCommunity',['data'=>$teacher]);
    }



    /**
     * @param $request
     * 执行添加社区推荐
     */
    public function doAddRecommendCommunity(Request $request){
        $input = $request->except('_token');
        if(DB::table('recteacher')->where('userId',$request['userId'])->first()){
            return Redirect()->back()->withInput()->withErrors('名师不可重复推荐');
        }
        $input['created_at'] = $input['updated_at'] = Carbon::now();

        //判断推荐位是否存在
        $isExit = DB::table('recteacher')->where('sort',$request['sort'])->first();
        if($isExit){
            DB::table('recteacher')->where('id',$isExit->id)->update(['sort'=>0]);
        }

        $input['name'] = DB::table('users')->where('id',$request['userId'])->pluck('realname');

        if($res = DB::table('recteacher')->insertGetId($input)){
//            $this -> OperationLog("新增了社区名师推荐ID为{$res}的信息", 1);
            return Redirect()->to('admin/users/recommendCommunityList')->with('status','添加社区推荐成功');
        }else{
            return Redirect()->back()->withInput()->withErrors('添加社区推荐失败！');
        }
    }


    /*
     *@param $id
     * 编辑社区推荐
     *
     */

    public function editRecommendCommunity($id){
        $data = DB::table('recteacher')->where('id',$id)->first();
        return view('admin.users.recommend.editRecommendCommunity')->with('data',$data);
    }


    /**
     * @param $request
     * 修改社区推荐
     */
    public function doEditRecommendCommunity(Request $request){
        $input = $request->except('_token');
        $input['updated_at'] = Carbon::now();
        //判断推荐位是否存在
        $isExit = DB::table('recteacher')->where('sort',$request['sort'])->first();
        if($isExit){
            DB::table('recteacher')->where('id',$isExit->id)->update(['sort'=>0]);
        }
        $res = DB::table('recteacher')->where('id',$input['id'])->update($input);
        if($res){
//            $this -> OperationLog("修改了社区名师推荐ID为{$request['id']}的信息", 1);
            return Redirect()->to('admin/users/recommendCommunityList')->with('status','修改社区推荐成功');
        }else{
            return Redirect()->back()->withInput()->withErrors('修改社区推荐失败！');
        }
    }



    /**
     * 删除
     */
    public function delRecommendCommunity($id){
        if(DB::table('recteacher')->where('id',$id)->delete()){
//            $this -> OperationLog("删除了社区名师推荐D为{$id}的信息", 1);
            return Redirect()->to('admin/users/recommendCommunityList')->with('status','删除社区推荐成功');
        }else{
            return Redirect()->back()->withInput()->withErrors('删除社区推荐失败！');
        }
    }



}
