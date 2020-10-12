<?php

namespace App\Models\Admin\Portal;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $table = 'category';
  protected $fillable = ['id', 'name', 'parent_id','description','sort', 'created_at', 'updated_at', 'deleted_at'];
  protected $dateFormat = 'U';
  protected $guarded = ['updated_at', 'created_at'];


  //验证数据有效方法
  public function validatePost(array $data)
  {
    $rules   = [
        'name' => 'required',
    ];
    $message = [
        'name.required' => ':attribute必须填',
    ];
    $field   = [
        'name' => '菜单名称',
    ];
    return Validator::make($data, $rules, $message, $field);
  }
}
