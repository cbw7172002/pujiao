<?php

namespace App\Http\Controllers\admin\aboutUs;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon;
use Illuminate\Support\Facades\Input;

class firmIntroController extends Controller{

    /**
     * 列表
     */
    public function FirmIntroList(){

        $firmIntro = DB::table('about')->get();

        return view('admin.aboutus.firmintro.firmintroList')->with('firmIntro',$firmIntro);
    }

    //修改页面
    public function editfirmIntro($id){
        $data = DB::table('about')->where('id',$id)->first();
        return view('admin.aboutus.firmintro.editfirmintro')->with('data',$data);
    }


    //修改方法
    public function editsfirmIntro(Request $request){
        $input = Input::except('_token');
        $input['updated_at'] = Carbon\Carbon::now();
//        dd($input);
        $res = DB::table('about')->where('id',$input['id'])->update($input);
        if($res){
            $this -> OperationLog("修改了公司介绍ID为{$input['id']}的信息", 1);
            return redirect('admin/message')->with(['status'=>'修改成功','redirect'=>'aboutUs/firmIntroList']);
        }else{
            return redirect()->back()->withInput()->withErrors('修改失败！');
        }

    }



}
