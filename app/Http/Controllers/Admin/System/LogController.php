<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Admin\System\Log;
use Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Ip;
use Session;

class LogController extends CommonController
{
    /**
     * 管理员日志列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, Log $log)
    {

        $param = $request->all();
        $where = $log;

        //筛选用户
        if (isset($param['user_id'])) {
            $where = $where->where('user_id', '=', $param['user_id']);
        }
        //筛选IP地址
        if (isset($param['ip'])) {
            $where = $where->where('login_ip', '=', $param['ip']);
        }


        //输入开始时间 输入结束时间 都不为空
        if (!empty($startTime) && !empty($endTime)) {
            $where = $where->where([
                ['login_at', '>=', $startTime],
                ['login_at', '<=', $endTime]
            ]);
        } else {
            //输入开始时间 不为空
            if (!empty($startTime)) {
                $where = $where->where(
                    'login_at', '>=', $startTime
                );
            }
            //输入结束时间 不为空
            if (!empty($endTime)) {
                $where = $where->where(
                    'login_at', '<=', $endTime
                );
            }
        }

        $data = $where->orderBy('id', 'desc')
            ->paginate(20);

        $user_list = Log::getUserList();

        return view('admin.system.log.index', [
            'data'       => $data,
            'user_list'  => $user_list,
            'start_time' => isset($param['start_time']) ? $param['start_time'] : '',
            'end_time'   => isset($param['end_time']) ? $param['end_time'] : '',
            'user_id'    => isset($param['user_id']) ? $param['user_id'] : '',
            'ip'         => isset($param['ip']) ? $param['ip'] : '',
        ]);
    }

    /**
     * 删除一条日志
     * @param $id  int 日志id
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logDel($id)
    {
        $log = Log::find($id);
        if ($log->is_system !== null) {
            return $this->error(url('admin/system/log/index'), '没有登录日志');
        }
        $log->delete();
        if ($log) {
            return $this->success(url('admin/system/log/index'), '删除成功');
        } else {
            return $this->error('', '删除失败');
        }

    }

}