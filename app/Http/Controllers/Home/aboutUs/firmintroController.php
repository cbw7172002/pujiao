<?php

namespace App\Http\Controllers\Home\aboutUs;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class firmintroController extends Controller{

    /**
     *列表
     */
    public function index($type){
        return view('home.aboutUs.aboutus')->with('type',$type);
    }

    //公司介绍
    public function getListone(){
        $data = DB::table('about')->select('id','title','content')->where('type',0)->get();
            if($data){
                return response()->json(['statuss'=>true,'data'=>$data]);
            }else{
                return response()->json(['statuss'=>false]);

            }
    }

    //联系我们
    public function getListtwo(){
        $data = DB::table('about')->select('id','title','content')->where('type',1)->get();
        if($data){
            return response()->json(['statuss'=>true,'data'=>$data]);
        }else{
            return response()->json(['statuss'=>false]);

        }
    }

    //常见问题
    public function getListthree(){
        $data = DB::table('about')->select('id','title','content')->where('type',2)->get();
        if($data){
            return response()->json(['statuss'=>true,'data'=>$data]);
        }else{
            return response()->json(['statuss'=>false]);

        }
    }


    //用户协议
    public function getListfour(){
        $data = DB::table('about')->select('id','title','content')->where('type',3)->get();
        if($data){
            return response()->json(['statuss'=>true,'data'=>$data]);
        }else{
            return response()->json(['statuss'=>false]);

        }
    }

    //友情链接
    public function getListfive(){
        $data = DB::table('link')->select('id','title','url','path')->where('status',0)->orderBy('postion','asc')->get();
        if($data){
            return response()->json(['statuss'=>true,'data'=>$data]);
        }else{
            return response()->json(['statuss'=>false]);

        }
    }

    /**
     * @param $type 类型
     * @return resource
     * 移动端关于我们
     **/
    public function appAbouts($type){
        return view('home.aboutUs.appAbouts')->with('type',$type);
    }


}
