<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Log;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function OperationLog($action, $isLogin = 1)
    {
        try {
            $tableName = 'operation' . date('_m', time());
            $data = [
                'username' => Auth::user()->username,
                'type' => $isLogin,
                'action' => $action,
                'client_ip' => $_SERVER['REMOTE_ADDR'],
                'create_at' => time()
            ];
            DB::table($tableName)->insert($data);
        } catch (\Exception $e) {
            Log::info(Auth::user()->username . ' 操作 ' . $action . ' 失败。 原因：' . $e->getMessage());
        }
    }

    protected function OperationOrderLog($action, $isLogin = 1)
    {
        try {
            $tableName = 'operation' . date('_m', time());
            $data = [
                'username' => '',
                'type' => $isLogin,
                'action' => $action,
                'client_ip' => $_SERVER['REMOTE_ADDR'],
                'create_at' => time()
            ];
            DB::table($tableName)->insert($data);
        } catch (\Exception $e) {
            Log::info(' 操作 ' . $action . ' 失败。 原因：' . $e->getMessage());
        }
    }
}
