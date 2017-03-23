<?php

namespace App\Http\Controllers\Home\community;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class newlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.community.newlist');
    }


    //新闻列表数据接口
    public function getnewlist($pageNumber,$pageSize){
        $skip = ($pageNumber-1) * $pageSize;
        $getnewlist = DB::table('news')->orderBy('created_at','desc')->where('status',0)->skip($skip)->take($pageSize)->get();
        $count = DB::table('news')->select('id')
            ->where('status',0)
            ->count();
        if($getnewlist){
            foreach ($getnewlist as $k => $v) {
                //只保留 年月日
                $created_ats = explode(" ", $v->created_at);
                $data['data'][] = [
                    'id' => $v->id,
                    'title' => $v->title,
                    'description' => $v->description,
                    'time'  => $created_ats[0]
                ];
            }
            return response()->json(['statuss'=>true,'data'=>$data['data'],'count'=>$count]);
        }else {
            return response()->json(['statuss'=>false]);
        }

    }

    /**
     * 新闻资讯详情列表
     */
    public function newdetail($id){
        return view('home.community.newdetail');
    }

    //新闻资讯详情数据接口
    public function getnewdetail($id){
        $getnewdetail = DB::table('news')->where('id',$id)->get();
        if($getnewdetail){
            foreach($getnewdetail as $k => $v){
                //只保留 年月日
                $created_ats = explode(" ", $v->created_at);
                $data['data'][] =[
                    'id' => $v->id,
                    'title' => $v->title,
                    'content' => $v->content,
                    'time'  => $created_ats[0]
                ];
            }
            $data['statuss'] = true;
        }else{
            $data['statuss'] = false;
        }
        echo json_encode($data);
    }


}
