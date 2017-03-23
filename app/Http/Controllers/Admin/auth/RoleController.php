<?php

namespace App\Http\Controllers\admin\auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bican\Roles\Models\Role;
use DB;
use Validator;
use Carbon\Carbon;

class RoleController extends Controller
{
    /**
     * 角色列表
     *
     * @return \Illuminate\Http\Response
     */
    public function roleList()
    {
        $roleList = DB::table('roles') -> select('id', 'slug', 'created_at') -> where('level', '<=', \Auth::user() -> level()) -> orderBy('id', 'asc') -> paginate(10);
        return view('admin.auth.role.roleList') -> with('roleList', $roleList);
    }


    /**
     * 添加角色
     *
     * @return \Illuminate\Http\Response
     */
    public function addRole()
    {
        return view('admin.auth.role.addRole');
    }


    /**
     * 创建角色
     *
     * @return \Illuminate\Http\Response
     */
    public function createRole(Request $request)
    {
        $validator = $this -> validator($request -> all());
        if ($validator -> fails()) {
            return Redirect() -> back() -> withInput($request -> all()) -> withErrors($validator);
        }
        $request['name'] = $request['slug'];
        $request['created_at'] = Carbon::now();
        $result = DB::table('roles') -> insertGetId($request -> except('_token'));
        if ($result) {
            $this -> OperationLog('创建了“'. $request['name'] .'”角色');
            return Redirect() -> to('/admin/auth/roleList') -> with('message', '添加成功');
        } else {
            return Redirect() -> back() -> withInput($request -> except('_token')) -> withErrors('添加失败');
        }
    }


    /**
     * 删除角色
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteRole($roleID)
    {
        $result = \DB::table('roles') -> where('id', $roleID) -> delete();
        if ($result) {
            $this -> OperationLog('删除了角色-'.$roleID);
            return Redirect() -> to('/admin/auth/roleList') -> with('message', '删除成功');
        } else {
            return Redirect() -> to('/admin/auth/roleList') -> with('error', '删除失败');
        }
    }


    /**
     * 编辑角色
     *
     * @return \Illuminate\Http\Response
     */
    public function editRole($roleID)
    {
        $result = DB::table('roles') -> select('id', 'slug') -> where('id', $roleID) -> first();
        return view('admin.auth.role.editRole') -> with('roleInfo', $result);
    }


    /**
     * 修改角色
     *
     * @return \Illuminate\Http\Response
     */
    public function updateRole(Request $request)
    {
        $validator = $this -> validator($request -> all());
        if ($validator -> fails()) {
            return Redirect() -> back() -> withInput($request -> all()) -> withErrors($validator);
        }
        $request['name'] = $request['slug'];
        $result = DB::table('roles') -> where('id', $request['id']) -> update($request -> except('_token'));
        if ($result) {
            $this -> OperationLog('修改了“'. $request['name'] .'”角色');
            return Redirect() -> to('/admin/auth/roleList') -> with('message', '修改成功');
        } else {
            return Redirect() -> back() -> withInput($request -> except('_token')) -> with('error', '修改失败');
        }
    }


    /**
     * 查看角色用户
     *
     * @return \Illuminate\Http\Response
     */
    public function checkRoleUser($roleID)
    {
        $userList = DB::table('users')
                  -> join('role_user', 'users.id', '=', 'role_user.user_id')
                  -> join('roles', 'role_user.role_id', '=', 'roles.id')
                  -> select('users.username', 'role_user.id', 'role_user.created_at') -> where(['roles.id' => $roleID, 'users.type' => 3]) -> orderBy('role_user.id', 'asc') -> paginate(10);
        return view('admin.auth.role.checkRoleUser') -> with('userList', $userList) -> with('roleID', $roleID);
    }


    /**
     * 删除角色用户
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteRoleUser($type, $roleID)
    {
        $tableName = intval($type) ? 'role_user' : 'permission_role';
        $result = \DB::table($tableName) -> where('id', $roleID) -> delete();
        if ($result) {
            $this -> OperationLog('删除了角色用户-'.$roleID);
            return Redirect() -> back() -> with('message', '撤销成功');
        } else {
            return Redirect() -> back() -> with('error', '撤销失败');
        }
    }


    /**
     * 添加角色用户
     *
     * @return \Illuminate\Http\Response
     */
    public function addRoleUser($roleID)
    {
        return view('admin.auth.role.addRoleUser') -> with('roleID', $roleID);
    }


    /**
     * 创建角色用户
     *
     * @return \Illuminate\Http\Response
     */
    public function createRoleUser(Request $request)
    {
        $request['user'] = explode(',', $request['user']);
        foreach ($request['user'] as $key => $value) {
            $result = DB::table('role_user') -> insertGetId(['role_id' => $request['roleID'], 'user_id' => $value, 'created_at' => Carbon::now()]);
            if (!$result) {
				return Redirect() -> back() -> withInput($request -> except('_token')) -> withErrors('添加失败');
			}
        }
        if ($result) {
            $this -> OperationLog('添加了“'. implode(', ', $request['user']) .'”角色用户');
            return Redirect() -> to('/admin/auth/checkRoleUser/'.$request['roleID']) -> with('message', '添加成功');
        } else {
            return Redirect() -> back() -> withInput($request -> except('_token')) -> withErrors('添加失败');
        }
    }


    /**
     * 获取部门或者职位信息
     *
     * @return \Illuminate\Http\Response
     */
    public function getList($departmentID = null)
    {
        if ($departmentID) {
            $result = DB::table('post') -> select('id', 'postName') -> where('parentId', $departmentID) -> orderBy('id', 'asc') -> get();
        } else {
            $result = DB::table('department') -> select('id', 'departName') -> orderBy('id', 'asc') -> get();
        }
        if ($result) {
            return Response() -> json(["type" => true, "data" => $result]);
        } else {
            return Response() -> json(["type" => false]); 
        }
    }


    /**
     * 获取用户信息
     *
     * @return \Illuminate\Http\Response
     */
    public function getUser($type, $id, $roleID)
    {
        $condition = intval($type) ? 'departId' : 'postId';
        $roleUser = DB::table('role_user') -> select('user_id') -> where('role_id', $roleID) -> get();
        $notIn = [];
        foreach ($roleUser as $key => $value) {
			$notIn[] = $value -> user_id;
		}
        $result = DB::table('users') -> select('id', 'username') -> where([$condition => $id, 'type' => 3]) -> whereNotIn('id', array_unique($notIn)) -> get();
        $result || $result = [['username' => '暂无数据']];
        if ($result) {
            return Response() -> json(["type" => true, "data" => $result]);
        } else {
            return Response() -> json(["type" => false]); 
        }
    }


    /**
     * 查看角色权限
     *
     * @return \Illuminate\Http\Response
     */
    public function checkRolePermission($roleID)
    {
        $permissionList = DB::table('permissions')
                  -> join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
                  -> join('roles', 'permission_role.role_id', '=', 'roles.id')
                  -> select('permissions.model', 'permissions.description', 'permission_role.id', 'permission_role.created_at') 
                  -> where(['roles.id' => $roleID]) -> orderBy('permission_role.id', 'asc') -> paginate(10);
        return view('admin.auth.role.checkRolePermission') -> with('permissionList', $permissionList) -> with('roleID', $roleID);
    }


    /**
     * 添加角色权限
     *
     * @return \Illuminate\Http\Response
     */
    public function addRolePermission($roleID)
    {
        return view('admin.auth.role.addRolePermission') -> with('roleID', $roleID);
    }


    /**
     * 创建角色权限
     *
     * @return \Illuminate\Http\Response
     */
    public function createRolePermission(Request $request)
    {
        $request['permission'] = explode(',', $request['permission']);
        foreach ($request['permission'] as $key => $value) {
            $result = DB::table('permission_role') -> insertGetId(['role_id' => $request['roleID'], 'permission_id' => $value, 'created_at' => Carbon::now()]);
            if (!$result) return Redirect() -> back() -> withInput($request -> except('_token')) -> withErrors('添加失败');
        }
        if ($result) {
            $this -> OperationLog('添加了“'. implode(', ', $request['permission']) .'”角色权限');
            return Redirect() -> to('/admin/auth/checkRolePermission/'.$request['roleID']) -> with('message', '添加成功');
        } else {
            return Redirect() -> back() -> withInput($request -> except('_token')) -> withErrors('添加失败');
        }
    }


    /**
     * 获取操作权限信息
     *
     * @return \Illuminate\Http\Response
     */
    public function getPermissionInfo($modelName = null, $roleID = null)
    {
        if ($modelName) {
            $result = DB::table('permission_role') -> select('permission_id') -> where('role_id', $roleID) -> get();
            $in = [];
            foreach ($result as $key => $value) $in[] = $value -> permission_id;
            $result = DB::table('permissions') -> select('id', 'description') -> where('model', $modelName) -> whereNotIn('id', array_unique($in)) -> orderBy('id', 'asc') -> get();
            $result || $result = [['description' => '暂无数据']];
        } else {
            $result = DB::table('permissions') -> select('model') -> orderBy('id', 'asc') -> groupBy('model') -> get();
        }
        if ($result) {
            return Response() -> json(["type" => true, "data" => $result]);
        } else {
            return Response() -> json(["type" => false]); 
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
        ];

        $messages = [
            'slug.required' => '请输入角色名称',
            'slug.max' => '角色名称最多24位',
        ];

        return Validator::make($data, $rules, $messages);
    }
}
