<?php

namespace App\Http\Controllers\Admin\users;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class friendsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function focusList($id,$status=null)
    {
        //我的关注列表
        $data = DB::table('friends as f')
            ->join('users as u','u.id','=','f.toUserId')
            ->select('f.id as friendId','f.toUserId as deleteId','f.fromUserId as id','u.username','u.checks','u.phone','u.type','u.pic','u.created_at','u.updated_at')
            ->where('f.fromUserId','=',$id)
            ->orderBy('f.toUserId','desc')
            ->paginate(10);
        $data->status = $status;
        return view('admin.users.focusList',compact('data'));
    }

    /*
     *我的关注删除
     */
    public function delete($userId,$id)
    {
        //$userId 关注用户id   $id == friends表id
        if(DB::table('friends')->delete($id)){
            return redirect('admin/message')->with(['status'=>'删除关注成功','redirect'=>$_SERVER['HTTP_REFERER']]);
        }else{
            return redirect('admin/message')->with(['status'=>'删除关注失败','redirect'=>$_SERVER['HTTP_REFERER']]);
        }
    }



    /**
     * Display the specified resource.friendsList
     */
    public function friendsList($id,$status=null)
    {
        //$id  === toUserId
        $data = DB::table('friends as f')
            ->join('users as u','u.id','=','f.fromUserId')
            ->select('f.id as friendId','f.fromUserId as deleteId','f.toUserId as id','u.username','u.checks','u.phone','u.type','u.pic','u.created_at','u.updated_at')
            ->where('f.toUserId','=',$id)
            ->orderBy('f.fromUserId','desc')
            ->paginate(10);
        $data->status = $status;
//        dd($data);
        return view('admin.users.friendsList',compact('data'));
    }


    /**
     *我的好友删除
     */
    public function destroy($userId,$id)
    {
        //$userId 好友用户id   $id == friends表id
        if(DB::table('friends')->delete($id)){
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>$_SERVER['HTTP_REFERER']]);
        }else{
            return redirect('admin/message')->with(['status'=>'删除失败','redirect'=>$_SERVER['HTTP_REFERER']]);
        }
    }
}
