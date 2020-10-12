<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Common\ComOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParameterController extends CommonController
{
    /**
     * 参数列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, ComOption $comOption)
    {

        $param = $request->all();
        $where = $comOption;

        //筛选渠道链接
        if (isset($param['name'])) {
            $where = $where->where('name', 'like', '%' . trim($param['name']) . '%');
        }

        $data = $where->paginate(20);

        return view('admin.system.parameter.index', [
            'data' => $data,
            'name' => isset($param['name']) ? $param['name'] : '',
        ]);
    }

    /**
     * 创建参数
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function parameterAdd(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('admin/system/parameter/add');
        }
        $data      = $request->all();
        $rules     = [
            'name'    => 'required|unique:com_options,name',
            'options' => 'required'
        ];
        $message   = [
            'name.required'    => ':attribute必填',
            'name.unique'      => '数据库中已经存在相同:attribute,请重新输入',
            'options.required' => '状态必填'

        ];
        $field     = [
            'name'    => '名称',
            'options' => '状态'
        ];
        $validator = Validator::make($data, $rules, $message, $field);
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $content  = $warnings->first();
            return $this->error('', $content);
        }
        $obj       = new ComOption();
        $obj->name = request('name');
        $data      = request('options');

        $data = explode(',', $data);
        for ($i = 1; $i < count($data); $i++) {
            $arrtmp      = explode(':', $data[($i - 1)]);
            $options[$i] = $arrtmp[1];
        }
        $obj->options     = $options;
        $obj->description = request('description');

        $obj->save();

        if ($obj) {
            return $this->success(url('admin/system/parameter/index'), '添加成功');
        } else {
            return $this->error('', '添加失败，请重新添加');
        }

    }

    /**
     * 编辑参数
     * @param Request $request
     * @param         $id int 参数id
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function parameterEdit(Request $request, $id)
    {
        if (request()->isMethod('POST')) {
            $data      = $request->all();
            $rules     = [
                'name'    => 'required',
                'options' => 'required'
            ];
            $message   = [
                'name.required'    => '名称必填',
                'options.required' => ':attribute必填'

            ];
            $field     = [
                'name'    => '名称',
                'options' => '状态'
            ];
            $validator = Validator::make($data, $rules, $message, $field);
            if ($validator->fails()) {
                $warnings = $validator->messages();
                $content  = $warnings->first();
                return $this->error('', $content);
            }
            $obj       = ComOption::find($id);
            $obj->name = request('name');
            $data      = request('options');

            $data = str_replace('""', '', $data);
            $data = explode(',', $data);
            unset($data[count($data) - 1]);
            for ($i = 1; $i <= count($data); $i++) {
                $arrtmp      = explode(':', $data[($i - 1)]);
                $options[$i] = $arrtmp[1];
            }
            $obj->options     = $options;
            $obj->description = request('description');
            $obj->save();
            if ($obj) {
                return $this->success(url('admin/system/parameter/index'), '修改成功');
            } else {
                return $this->error('', '修改失败，请重新添加');
            }

        }
        return view('admin.system.parameter.edit', [
            'parameter' => ComOption::find($id)
        ]);


    }
}