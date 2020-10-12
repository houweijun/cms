<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Http\Controllers\Common\CommonController;
use App\Models\Admin\Portal\Category;
use Bootstrap\Common\Tree;
use Illuminate\Http\Request;

class CategoryController extends CommonController
{
  /**
   * 文章分类首页
   * @param Request $request
   * @param Category $category
   */
  public function index(Request $request, Category $category, Tree $tree)
  {
    $param = $request->all();
    $where = $category;
    //搜索词不为空
    if (isset($param['name']) && !empty($param['name'])) {
      $where    = $where->where('name', 'like', '%' . trim($param['name']) . '%');
      $category = $where->orderBy('sort', 'asc')->paginate(20);

    } else { //搜索词为空   显示目录树
      $data       = $where->orderBy('sort', 'asc')->get()->toArray();
      $tree->icon = ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ '];
      $tree->nbsp = '&nbsp;&nbsp;&nbsp;';


      foreach ($data as $key => $value) {
        $data[$key]['parent_id_node'] = ($value['parent_id']) ? ' class="child-of-node-' . $value['parent_id'] . '"' : '';
        $data[$key]['style']          = empty($value['parent_id']) ? '' : 'display:none;';
        $data[$key]['created_at']     = date('Y-m-d H:i:s');
        $data[$key]['add_submenu']    = '<a class="btn btn-outline btn-primary mr5" href="' . url('admin/portal/category/add', [$value['id']]) . '"><i class="fa fa-plus-square-o"></i>添加子分类</a>';
        $data[$key]['edit']           = '<a class="btn btn-outline btn-warning mr5" href="' . url('admin/portal/category/edit', [$value['id']]) . '"><i class="fa fa-paste"></i>编辑</a>';
        $data[$key]['delete']         = '<a class="btn btn-outline btn-danger mr5" href="javascript:;"
                           onclick="return checkDel(\'删除分类\',\'你确定要删除分类' . $value['name'] . '吗?\',\'/admin/portal/category/del\',this)"
                           data-id="' . $value['id'] . '">
                           <i class="fa fa-trash-o fa-lg"></i>删除
                        </a>';
        $data[$key]['order']          = '<input name="sort" type="text" size="3" value="' . $value['sort'] . '"
                                     class="input input-order" data-id="' . $value['id'] . '" onchange="listOrder(this,\'/admin/portal/category/order\')" />';
      }

      $tree->init($data);

      //排序权限判别
      if(roleShow('admin/portal/category/order')){
        $str1="<td style='padding-left:20px'>\$order</td>";
      }else{
        $str1 = '';
      }

      //操作权限判别
      if(roleShow('admin/portal/category/add')||roleShow('admin/portal/category/edit')||roleShow('admin/portal/category/del')){
        //菜单添加按钮
        if(roleShow('admin/portal/category/add')){
          $str3 = "\$add_submenu";
        }else{
          $str3= '';
        }
        //菜单编辑按钮
        if(roleShow('admin/portal/category/edit')){
          $str4 = "\$edit";
        }else{
          $str4= '';
        }
        //菜单删除按钮
        if(roleShow('admin/portal/category/del')){
          $str5 = "\$delete";
        }else{
          $str5= '';
        }
        $str2 = "<td>".$str3.$str4.$str5."</td>";
      }else{
        $str2 ='';
      }
      //分类组合
      $str = "<tr id='node-\$id' \$parent_id_node style='\$style'>".
          $str1
          ."<td>\$id</td>
                        <td>\$spacer\$name</td>
                        <td>\$description</td>
                        <td>\$created_at</td>
                        ".$str2."
                    </tr>";

      $category = $tree->getTree(0, $str);
    }


    return view('admin/portal/category/index', [
        'name'    => isset($param['name']) ? $param['name'] : '',
        'category' => $category
    ]);
  }


  /**
   * 添加文章分类
   * @param Request $request
   * @param Category $category
   */
  public function add(Request $request, Category $category,$parentId = '0', Tree $tree)
  {
    if ($request->isMethod('get')) {
      $data  = $category->orderBy('sort', 'asc')->get()->toArray();
      $array = [];
      foreach ($data as $k => $v) {
        $v['selected'] = $v['id'] == $parentId ? 'selected' : '';
        $array[]       = $v;
      }
      $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
      $tree->init($array);
      $selectCategory = $tree->getTree(0, $str);
      return view('admin/portal/category/add', [
          'select_category' => $selectCategory
      ]);
    }

    //接收数据
    $data = $request->all();
    $data = getData($data);

    //验证数据有效性
    $validator = $category->validatePost($data);
    $titleNum  = $category->where('name', $data['name'])->count();
    if ($titleNum >= 1) {
      $validator->after(function ($validator) {
        $validator->errors()->add('name', '文章分类名称,请重新输入');
      });
    }
    if ($validator->fails()) {
      $warnings = $validator->messages();
      $content  = $warnings->first();
      return $this->error('', $content);
    }
    $data['created_at'] = time();

    $id = $category->insertGetId($data);
    //菜单入库判断是否正确
    if ($id) {
      return $this->success(url('admin/portal/category/index'), '添加成功');
    } else {
      return $this->error('', '添加失败，请重新添加');
    }
  }

  /**
   * 编辑文章分类
   * @param Request $request
   * @param Category $category
   */
  public function edit(Request $request, Category $category,$id, Tree $tree)
  {
    if ($request->isMethod('get')) {
      $data  = $category->orderBy('sort', 'asc')->get()->toArray();
      $rs    = $category->where('id', $id)->first();
      $array = [];
      foreach ($data as $k => $v) {
        $v['selected'] = $v['id'] == $rs['parent_id'] ? 'selected' : '';
        $array[]       = $v;
      }
      $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
      $tree->init($array);
      $selectCategory = $tree->getTree(0, $str);
      return view('admin/portal/category/edit', [
          'select_category' => $selectCategory,
          'data'            => $rs
      ]);
    }

    //接收数据
    $data = $request->all();
    $data = getData($data);

    //验证数据有效性
    $validator = $category->validatePost($data);
    $titleNum  = $category->where('name', $data['name'])->count();

    if ($titleNum > 1) {
      $validator->after(function ($validator) {
        $validator->errors()->add('name', '文章分类名称,请重新输入');
      });
    }


    if ($validator->fails()) {
      $warnings = $validator->messages();
      $content  = $warnings->first();
      return $this->error('', $content);
    }

    $data['updated_at'] = time();

    $id = $category->where('id', $id)->update($data);
    //菜单入库判断是否正确
    if ($id) {
      return $this->success(url('admin/portal/category/index'), '修改成功');
    } else {
      return $this->error('', '修改失败，请重新修改');
    }
  }


  /**
   * 删除文章分类
   * @param Request $request
   * @param Category $category
   */
  public function del(Request $request, Category $category)
  {
    $id    = $request->input('id');
    $count = $category->where('parent_id', $id)->count();
    if ($count > 0) {
      return $this->json_encode(0, '该分类下有子分类,不能删除');
    }

    $delRes = $category->where('id',$id)->delete();

    if ($delRes) {//成功提交
      DB::commit();
      return $this->json_encode(1, '删除成功');
    } else {//失败回滚
      DB::rollback();
      return $this->json_encode(0, '删除失败');
    }

  }


  /**
   * 排序文章分类
   * @param Request $request
   * @param Category $category
   */
  public function order(Request $request, Category $category)
  {
    $id   = $request->input('id');
    $sort = $request->input('sort');
    if (!is_numeric($sort)) {
      return $this->json_encode(0, '更新失败');
    }
    $re = $category->where('id', $id)->update(['sort' => $sort]);
    if ($re) {
      return $this->json_encode(1, '更新成功');
    } else {
      return $this->json_encode(0, '更新失败');
    }
  }
}
