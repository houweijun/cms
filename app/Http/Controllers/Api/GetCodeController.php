<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Common\CommonController;
use App\Models\Admin\TaxCode\TaxCode;
use App\Models\Admin\TaxCode\CodePageManage;
use Illuminate\Http\Request;

class GetCodeController extends CommonController
{
    /**
     * 获取token
     * @return string
     */
    public function token()
    {
        try {
            $str    = random_string(6);
            $strNew = $str . config('zhops.admin.security_key');
            $data   = str_encode($strNew);
            return $this->json_encode(1, $data);
        } catch (\Exception $e) {
            return $this->json_encode(0, '系统内部错误');
        }

    }

    /**
     * 获取展示的激活码礼包
     */
    public function show(TaxCode $code, Request $request)
    {
        try {
            $send_user = $request->input('send_user');
            $id        = $request->input('id');
            //礼包用户 参数没有传 报参数错误
            if (empty($send_user) || empty($id)) {
                return $this->json_encode(0, '参数错误');
            }
            $data = $code
                ->select('id', 'name', 'logo_url', 'url', 'description', 'count_num', 'send_num', 'send_user')
                ->where(['status' => 1, 'id' => $id])
                ->first();

            //假如数据为空 直接返回 信息
            if (empty($data)) {
                return $this->json_encode(0, '礼包暂未开放');
            }

            $data['remain_num'] = $data['count_num'] - $data['send_num'];
            $data['send_user']  = json_decode($data['send_user'], true);
            if ((!empty($data['send_user']) && in_array($send_user, $data['send_user'])) || ($data['remain_num'] <= 0)) {
                $data['status'] = 2;
            } else {
                $data['status'] = 1;
            }
            unset($data['send_user']);
            return $this->json_encode(1, $data);
        } catch (\Exception $e) {
            return $this->json_encode(0, '系统内部错误');
        }

    }

    /**
     * 获取激活码详情
     * @param TaxCode $code
     * @param Request $request
     */
    public function details(TaxCode $code, Request $request)
    {
        try {
            $send_user = $request->input('send_user');
            $id        = $request->input('id');
            $status    = $request->input('status');
            //礼包用户 参数没有传 报参数错误
            if (empty($send_user) || empty($id) || empty($status)) {
                return $this->json_encode(0, '参数错误');
            }
            //礼包不可领取
            if ($status == 2) {
                return $this->json_encode(1, '不可领取');
            }
            $data = $code->where(['status' => 1, 'id' => $id])->first();
            if (!empty($data)) {
                $data = $data->toArray();
            }
            //礼包不存在
            if (empty($data)) {
                return $this->json_encode(2, '该礼包不存在');
            }

            $data['count_code'] = json_decode($data['count_code'], true);
            $data['send_code']  = json_decode($data['send_code'], true);
            $data['send_user']  = json_decode($data['send_user'], true);

            //假如数据为空 定义空数组
            if (empty($data['send_code'])) {
                $data['send_code'] = [];
            }
            //假如数据为空 定义空数组
            if (empty($data['send_user'])) {
                $data['send_user'] = [];
            }
            //不可领取
            if (!empty($data['send_user']) && in_array($send_user, $data['send_user'])) {
                return $this->json_encode(1, '不可领取');
            }

            //获取随机激活码
            $send_code = $this->rand_code($data['count_code'], $data['send_code']);

            //假如获取的新不在范围内 压入数组
            if (!in_array($send_code, $data['send_code'])) {
                array_push($data['send_code'], $send_code);
                array_push($data['send_user'], $send_user);
            }


            $data1 = [
                'send_code'  => json_encode($data['send_code'], true),
                'send_user'  => json_encode($data['send_user'], true),
                'send_num'   => $data['send_num'] + 1,
                'updated_at' => time(),
            ];

            $res = $code->where('id', $id)->update($data1);
            if ($res) {
                return $this->json_encode(3, $send_code);
            } else {
                return $this->json_encode(4, '领取失败');
            }

        } catch (\Exception $e) {
            return $this->json_encode(0, '系统内部错误');
        }

    }

    /**
     * 获取页面激活码详情
     * @param Request        $request
     * @param CodePageManage $codePageManage
     * @param TaxCode        $code
     */
    public function page(Request $request, CodePageManage $codePageManage, TaxCode $code)
    {
        try {
            $id        = $request->input('id');
            $send_user = $request->input('send_user');
            if (empty($id) || empty($send_user)) {
                return $this->json_encode(0, '参数错误');
            }
            $data = $codePageManage
                ->select('id', 'name', 'logo_url', 'code_array')
                ->where(['id' => $id, 'status' => 1])
                ->first();
            if (empty($data)) {
                return $this->json_encode(1, '该礼包集成页不存在');
            }
            $code_array = json_decode($data['code_array'], true);
            $code_id    = $code_array['code_id'];
            $code_name  = $code_array['code_name'];
            $code_des   = $code_array['code_des'];

            $codeData = $code
                ->select('id', 'count_num', 'send_num', 'send_user')
                ->where('status', 1)
                ->whereIn('id', $code_id)
                ->get()
                ->toArray();

            foreach ($codeData as $kk => $vv) {

                foreach ($code_id as $k => $v) {
                    if ($vv['id'] == $v) {
                        $codeData[$kk]['code_id']   = $code_id[$k];
                        $codeData[$kk]['code_name'] = $code_name[$k];
                        $codeData[$kk]['code_des']  = $code_des[$k];
                    }
                }

                $codeData[$kk]['remain_num'] = $vv['count_num'] - $vv['send_num'];
                $codeData[$kk]['send_user']  = json_decode($vv['send_user'], true);
                if ((!empty($codeData[$kk]['send_user']) && in_array($send_user, $codeData[$kk]['send_user'])) || ($codeData[$kk]['remain_num'] <= 0)) {
                    $codeData[$kk]['status'] = 2;
                } else {
                    $codeData[$kk]['status'] = 1;
                }
                unset($codeData[$kk]['send_user']);
            }

            $data['code_array'] = $codeData;
            return $this->json_encode(2, $data);
        } catch (\Exception $e) {
            return $this->json_encode(0, '系统内部错误');
        }
    }

    /**
     * 随机抽取激活码
     * @param $count_arr
     * @param $send_arr
     */
    public function rand_code($count_arr, $send_arr)
    {
        //计算差集
        $arr = array_diff($count_arr, $send_arr);
        //在差集中随机取元素
        $send_code = array_random($arr, 1)[0];
        return $send_code;
    }
}
