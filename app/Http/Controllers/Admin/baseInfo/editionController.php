<?php

namespace App\Http\Controllers\Admin\baseInfo;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use DB;
use Cache;


class editionController extends Controller{

    public function editionList(Request $request){

       $data = DB::table('studyedition')->select()->paginate(10);
       return view('admin.baseInfo.edition.editionList')->with('data',$data);
    }


    /**
     * 添加页面
     */
    public function addEdition(){

        return view('admin.baseInfo.edition.addEdition');
    }


    /**
     * 添加
     */
    public function doAddEdition(Request $request){
        $input = Input::except('_token');
        //验证
        $validate = $this->validator($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }

        $res = DB::table('studyedition')->insertGetId($input);
        if($res){
//            $this -> OperationLog("新增了年级ID为{$res}的信息", 1);
            if(Cache::has('App\Http\Controllers\Home\resource\getRessels\3')){
                Cache::forget('App\Http\Controllers\Home\resource\getRessels\3');
            }else{
                $data = DB::table('studyedition')->select('id','editionName')->orderBy('id','asc')->get();
                Cache::forever('App\Http\Controllers\Home\resource\getRessels\3', $data);
            }
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'baseInfo/editionList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }

    }


    /**
     * 编辑页面
     */
    public function editEdition($id){

        $data = DB::table('studyedition')->select()->where('id','=',$id)->first();

        return view('admin.baseInfo.edition.editEdition')->with('data',$data);

    }


    /**
     * 编辑
     */
    public function doEditEdition(Request $request){
        $input = Input::except('_token');
        //验证
        $validate = $this->validator_edit($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $res = DB::table('studyedition')->where('id',$input['id'])->update($input);

        if($res !== false){
//            $this -> OperationLog("修改了后台用户ID为{$request['id']}的信息", 1);
            if(Cache::has('App\Http\Controllers\Home\resource\getRessels\3')){
                Cache::forget('App\Http\Controllers\Home\resource\getRessels\3');
            }else{
                $data = DB::table('studyedition')->select('id','editionName')->orderBy('id','asc')->get();
                Cache::forever('App\Http\Controllers\Home\resource\getRessels\3', $data);
            }
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'baseInfo/editionList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }


    /**
     * 删除
     */
    public function delEdition($id){
        $res = DB::table('studyedition')->where('id',$id)->delete();
        if($res){
//            $this -> OperationLog("删除了后台用户ID为{$id}的信息", 1);
            if(Cache::has('App\Http\Controllers\Home\resource\getRessels\3')){
                Cache::forget('App\Http\Controllers\Home\resource\getRessels\3');
            }else{
                $data = DB::table('studyedition')->select('id','editionName')->orderBy('id','asc')->get();
                Cache::forever('App\Http\Controllers\Home\resource\getRessels\3', $data);
            }
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'baseInfo/editionList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }



    /**
     * 验证(添加)
     */
    protected function validator(array $data){
        $rules = [
            'editionName' => 'required',
        ];
        $messages = [
            'editionName.required' => '请输入版本',
        ];

        return \Validator::make($data, $rules, $messages);
    }



    /**
     * 验证(修改)
     */
    protected function validator_edit(array $data){
        $rules = [
            'editionName' => 'required',
        ];
        $messages = [
            'editionName.required' => '请输入版本',
        ];

        return \Validator::make($data, $rules, $messages);
    }




}

