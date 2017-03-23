<?php

namespace App\Http\Controllers\Admin\logs;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class indexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logList(Request $request)
    {
        //对应的表
        $tableName = 'operation'.date('_m', time());

        //定义搜索初值
        $search = [];
        $search['beginTime'] = null;
        $search['endTime'] = null;
        $search['type'] = null;
        $search['time'] = null;
        //搜索
        $query = \DB::table($tableName);

        if($request->type == 0){
            $query = $query->where('username','like','%'.trim($request->search).'%');
            $search['type'] = 0;
        }

        if($request->type == 1){
            $query = $query;
            $search['type'] = 1;
        }

        if($request->beginTime ){
            $query = $query->where('create_at','>=',strtotime($request->beginTime));
            $search['beginTime'] = $request->beginTime;
        }
        if($request->endTime){
            $query = $query->where('create_at','<=',strtotime($request->endTime));
            $search['endTime'] = $request->endTime;
        }

        if($request->time){
//            dd($request->time);
            $search['time'] = $request->time;
            $tableName = 'operation'.date('_m',strtotime($request->time));
            $query = \DB::table($tableName);

            if($request->type == 0){
                $query = $query->where('username','like','%'.trim($request->search).'%');
                $search['type'] = 0;
            }

            if($request->type == 1){
                $query = $query;
                $search['type'] = 1;
            }

            if($request->beginTime){
                $query = $query->where('create_at','>=',strtotime($request->beginTime));
                $search['beginTime'] = $request->beginTime;
            }
            if($request->endTime){
                $query = $query->where('create_at','<=',strtotime($request->endTime));
                $search['endTime'] = $request->endTime;
            }

            $data = $query->orderBy('id','desc')->paginate();
            $currentPage = $data->currentPage();
            $lastPage = $data->lastPage();
            $count = $data->count();

            return view('admin.logs.logList',compact('data','search','tableName'))->with('time',$request->time)->with('currentPage',$currentPage)->with('lastPage',$lastPage)->with('count',$count);
        }



        $data = $query->orderBy('id','desc')->paginate();
        $currentPage = $data->currentPage();
        $lastPage = $data->lastPage();
        $count = $data->count();
        return view('admin.logs.logList',compact('data','search','tableName'))->with('currentPage',$currentPage)->with('lastPage',$lastPage)->with('count',$count);

    }

    /**
     * Delete a listing of the resource.
     * @param   $tableName   对应表名
     * @param   $id          对应ID
     * @param   $time        搜索的时间（可选）
     * @return  \Illuminate\Http\Response
     */
    public function destroy($tableName,$id,$time=null)
    {
        //日志删除操作
        if(\DB::table($tableName)->delete($id)){
            return redirect()->back()->with(['status'=>'删除日志成功','time'=>$time]);
        }else{
            return redirect()->back()->with(['errors'=>'删除日志失败','time'=>$time]);
        }
    }


    /**
     * Multiple Delete a listing of the resource.
     * @param   $request
     * @param   $tableName   对应表名
     * @param   $time        搜索的时间（可选）
     * @return  \Illuminate\Http\Response
     */
    public function delete(Request $request,$tableName,$time=null)
    {
//        dd($request['count'] % 15);
        //验证 删除项不为空
        $rules = ['check' => 'required',];
        $messages = ['check.required' => '请选择删除项'];
        $validate = \Validator::make($request->all(),$rules,$messages);
        if($validate->fails()){
            return \Redirect::back()->withErrors($validate);
        }else{
            //日志多删除操作
//            dd($request['currentPage']);
            if(\DB::table($tableName)->whereIn('id',$request['check'])->delete()){
                if($request['lastPage'] == 1){
                    return redirect()->to('/admin/logs/logList'.'?'.'time='.$time)->with(['status'=>'删除日志成功','time'=>$time]);
                }
                if ($request['lastPage'] == $request['currentPage']){
                    if((count($request['check']) < $request['count'] % 15) || ($request['count'] % 15 == 0 && (count($request['check']) < 15))){
                        return redirect()->back()->with(['status'=>'删除日志成功','time'=>$time]);
                    }
                    return redirect()->to('/admin/logs/logList'.'?'.'page='.($request['currentPage']-1))->with(['status'=>'删除日志成功','time'=>$time]);
                }

                return redirect()->back()->with(['status'=>'删除日志成功','time'=>$time]);
            }else{
                return redirect()->back()->with(['errors'=>'删除日志失败','time'=>$time]);
            }
        }
    }


}
