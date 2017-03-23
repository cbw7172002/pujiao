<?php

namespace App\Http\Controllers\Admin\sensitive;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Cache;

class sensitiveController extends Controller
{
    /**
     *敏感词列表
     */
    public function sensitiveList(Request $request){
        $query = DB::table('sensitivewords');
        if($request['search']){
            $query = $query->where('word','like','%'.trim($request['search']).'%');
        }
        $data = $query->orderBy('id','desc')->paginate(10);
        return view('admin.sensitive.sensitiveList',['data'=>$data]);
    }

    /**
     *添加敏感词
     */
    public function addSensitive(){
        return view('admin.sensitive.addSensitive');
    }
    public function doAddSensitive(Request $request){
        $word = str_replace('，',',',$request['word']);
        $word = explode(',',$word);
        foreach($word as $value){
            $words[] = trim($value);
        }
        $word = array_filter(array_unique($words));//去重复数组 空数组
        $selwords = DB::table('sensitivewords')->lists('word');
        $insert = [];
        foreach($word as $val){
            if(!in_array($val,$selwords)){
                $newword[] =  $val; //新加且无重复的词
                $aword['word'] = $val;
                $aword['created_at'] = Carbon::now();
                $insert[] = $aword;
            }
        }
        if(!$insert){
            return redirect()->back()->withInput()->withErrors('请检查敏感词是否已存在或添加为空');
        }
        $data = DB::table('sensitivewords')->insert($insert);
        if($data){
//            $allwords = DB::table('sensitivewords')->lists('word');
            if(Cache::has('sensitive')){
                $before = Cache::get('sensitive');
                $allwords = array_merge($before,$newword);
                Cache::forever('sensitive', $allwords);
            }else{
                Cache::forever('sensitive', $newword);
            }
            return redirect()->back()->with('status','添加成功');
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败');
        }

    }

    /**
     *删除
     */
    public function delSensitive($id){
        $delword = DB::table('sensitivewords')->where('id',$id)->pluck('word');
        if(DB::table('sensitivewords')->where('id',$id)->delete()){
            if(Cache::has('sensitive')){
                $cache = Cache::get('sensitive');
                foreach($cache as $key=>$val){
                    if($delword == $val){
                        unset($cache[$key]);
                    }
                }
                Cache::forever('sensitive', $cache);
            }
            return redirect()->back()->with('status','删除成功');
        }else{
            return redirect()->back()->withErrors('删除失败');
        }
    }

    /**
     *批量删除
     */
    public function deletes(Request $request){
        $delword = DB::table('sensitivewords')->whereIn('id',$request['id'])->lists('word');
        if(DB::table('sensitivewords')->whereIn('id',$request['id'])->delete()){
            if(Cache::has('sensitive')){
                $cache = Cache::get('sensitive');
                foreach($cache as $key=>$val){
                    foreach($delword as $vv){
                        if($val == $vv){
                            unset($cache[$key]);
                        }
                    }
                }
                Cache::forever('sensitive', $cache);
            }
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     *一键生成缓存
     */
    public function onekey(){
        $allwords = DB::table('sensitivewords')->lists('word');
        if($allwords){
            Cache::forever('sensitive', $allwords);
        }
        return back();
    }
}
