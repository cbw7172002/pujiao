<?php

namespace App\Http\Controllers\admin\auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bican\Roles\Models\Permission;
use DB;
use Validator;
use Carbon\Carbon;

class PermissionController extends Controller
{
    /**
     * 操作权限列表
     *
     * @return \Illuminate\Http\Response
     */
    public function permissionList()
    {
        $permissionList = DB::table('permissions') -> select('id', 'slug', 'description', 'model', 'created_at') -> orderBy('id', 'asc') -> paginate(10);
        return view('admin.auth.permission.permissionList') -> with('permissionList', $permissionList);
    }


    /**
     * 添加操作权限
     *
     * @return \Illuminate\Http\Response
     */
    public function addPermission()
    {
        return view('admin.auth.permission.addPermission');
    }


    /**
     * 创建操作权限
     *
     * @return \Illuminate\Http\Response
     */
    public function createPermission(Request $request)
    {
        $validator = $this -> validator($request -> all());
        if ($validator -> fails()) {
            return Redirect() -> back() -> withInput($request -> all()) -> withErrors($validator);
        }
        $request['name'] = $request['slug'];
        $request['created_at'] = Carbon::now();
        $result = DB::table('permissions') -> insertGetId($request -> except('_token'));
        if ($result) {
            $this -> OperationLog('创建了“'. $request['name'] .'”操作权限');
            return Redirect() -> to('/admin/auth/permissionList') -> with('message', '添加成功');
        } else {
            return Redirect() -> back() -> withInput($request -> except('_token')) -> withErrors('添加失败');
        }
    }


    /**
     * 删除操作权限
     *
     * @return \Illuminate\Http\Response
     */
    public function deletePermission($permissionID)
    {
        $result = \DB::table('permissions') -> where('id', $permissionID) -> delete();
        if ($result) {
            $this -> OperationLog('删除了操作权限-'.$permissionID);
            return Redirect() -> to('/admin/auth/permissionList') -> with('message', '删除成功');
        } else {
            return Redirect() -> to('/admin/auth/permissionList') -> with('error', '删除失败');
        }
    }


    /**
     * 编辑操作权限
     *
     * @return \Illuminate\Http\Response
     */
    public function editPermission($permissionID)
    {
        $permissionInfo = DB::table('permissions') -> select('id', 'slug', 'description', 'model') -> where('id', $permissionID) -> first();
        return view('admin.auth.permission.editPermission') -> with('permissionInfo', $permissionInfo);
    }


    /**
     * 修改操作权限
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePermission(Request $request)
    {
        $validator = $this -> validator($request -> all());
        if ($validator -> fails()) {
            return Redirect() -> back() -> withInput($request -> all()) -> withErrors($validator);
        }
        $request['name'] = $request['slug'];
        $result = DB::table('permissions') -> where('id', $request['id']) -> update($request -> except('_token'));
        if ($result) {
            $this -> OperationLog('修改了“'. $request['name'] .'”操作权限');
            return Redirect() -> to('/admin/auth/permissionList') -> with('message', '修改成功');
        } else {
            return Redirect() -> back() -> withInput($request -> except('_token')) -> with('error', '修改失败');
        }
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'slug' => 'required|max:24',
            'description' => 'required|max:50',
            'model' => 'required|max:24',
        ];

        $messages = [
            'slug.required' => '请输入操作权限名称',
            'slug.max' => '操作权限名称最多24位',
            'description.required' => '请输入操作权限描述',
            'description.max' => '操作权限描述最多50位',
            'model.required' => '请输入所属模块',
            'model.max' => '所属模块最多24位',
        ];

        return Validator::make($data, $rules, $messages);
    }
}
