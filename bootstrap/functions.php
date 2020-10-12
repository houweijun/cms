<?php

use Bootstrap\Common\Local;
use Bootstrap\Common\phpQuery\phpQuery;
use Illuminate\Support\Facades\DB;

/**
 *   过滤_token
 * @param  array $data
 * @return array
 */
if (!function_exists('getData')) {
  function getData($data)
  {
    $data1 = array();
    foreach ($data as $k => $v) {
      if ($k === '_token' || $k == 'taglocation' || $k == 'tags') {
        continue;
      }
      if ($k == 'img' || $k == 'imgs') {
        continue;
      }
      $data1[$k] = $v;
    }
    return $data1;
  }
}

/**
 * 验证表单数据是否为空
 * @return bool
 */
if (!function_exists(' is_verify')) {
  function is_verify()
  {
    $bool = true;
    $array = $_POST;
    foreach ($array as $k => $v) {
      if ($v == '') {
        $bool = false;
      }
    }
    return $bool;
  }
}

/**
 * 处理成功跳转数据
 * @return array
 */
function success($url, $content, $time)
{
  return ['type' => 1, 'url' => $url, 'content' => $content, 'time' => $time];
}

/**
 * 处理失败跳转数据
 * @return array
 */
function error($url = null, $content = '操作失败', $time = 3)
{
  return ['type' => 2, 'url' => $url, 'content' => $content, 'time' => $time];
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
if (!function_exists('get_client_ip')) {
  function get_client_ip($type = 0, $adv = true)
  {
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL) return $ip[$type];
    if ($adv) {
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos) unset($arr[$pos]);
        $ip = trim($arr[0]);
      } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
      }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? [$ip, $long] : ['0.0.0.0', 0];
    return $ip[$type];
  }
}

/**
 * 浏览器
 */
function userBrowser()
{
  if (empty($_SERVER['HTTP_USER_AGENT'])) {
    return '其它';
  }
  if ((false == strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE)) {
    return 'IE浏览器';
  }
  if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 10.0')) {
    return 'IE浏览器';
  }
  if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0')) {
    return 'IE浏览器';
  }
  if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')) {
    return 'IE浏览器';
  }
  if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) {
    return 'IE浏览器';
  }
  if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')) {
    return 'IE浏览器';
  }
  if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'Edge')) {
    return 'Edge浏览器';
  }
  if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
    return '火狐浏览器';
  }
  if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
    return '谷歌浏览器';
  }
  if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')) {
    return 'Safari浏览器';
  }
  if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')) {
    return 'Opera浏览器';
  }
  if (false !== strpos($_SERVER['HTTP_USER_AGENT'], '360SE')) {
    return '360急速浏览器';
  }
  //微信浏览器
  return '其它';
}

/**
 * 权限的树状图
 */
function getTree($permissions, $id = 0, $count = 0)
{
  static $res = array();
  foreach ($permissions as $permission) {
    if ($permission->parent_id == $id) {
      $permission->count = $count;
      $res[] = $permission;
      getTree($permissions, $permission->id, $count + 1);
    }
  }
  return $res;
}

/**
 * Restful响应
 * @param  string $message 消息
 * @param  integer $status 状态
 * @param  array $body 响应体
 */
if (!function_exists("restful")) {
  function restful($status = 0, $message = '', $body = [])
  {
    if (is_array($message) or is_object($message)) {
      $body = $message;
      $message = 'success';
    }
    $content = [
        'status' => (int)$status,
        'message' => $message,
        'body' => $body,
        'timestamp' => time()
    ];
    return json_encode($content, JSON_UNESCAPED_UNICODE);
  }
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
if (!function_exists('get_client_ip')) {
  function get_client_ip($type = 0, $adv = true)
  {
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL) return $ip[$type];
    if ($adv) {
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos) unset($arr[$pos]);
        $ip = trim($arr[0]);
      } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
      }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? [$ip, $long] : ['0.0.0.0', 0];
    return $ip[$type];
  }
}

/**
 * 生成随机token
 * @return token
 */
if (!function_exists('getToken')) {
  function getToken($params)
  {
    $params = (array)$params;
    ksort($params);
    $params_string = '';
    foreach ($params as $name => $value) {
      $value = urldecode($value);
      $params_string .= "&{$name}={$value}";
    }
    $params_string = base64_encode(substr($params_string, 1));
    return hash_hmac('sha1', $params_string, 'fkzzz');
  }
}

/**
 * 验证手机号
 */
function isMobile($mobile)
{
  $preg = "/^1[34578]\d{9}$/";
  if (preg_match($preg, $mobile)) {
    return true;
  } else {
    return false;
  }
}

/**
 * 生成签名
 * @param  array $data
 * @return string
 */
function sign($data)
{
  $str = '';
  foreach ($data as $k => $v) {
    $str .= $v;
  }
  $re = md5(md5($str, true));
  return $re;
}

/**
 * 生成签名
 * @param  array $data
 * @param  string $sign 签名
 * @return bool
 */
function is_sign($data, $sign)
{
  $str = '';
  foreach ($data as $k => $v) {
    $str .= $v;
  }
  $re = md5($str);
  if ($re == $sign) {
    return true;
  }
  return false;
}

/**
 * 检查手机号码格式
 * @param $mobile  int  手机号码
 * @return bool
 */
function check_mobile($mobile)
{
  if (preg_match('/1[34578]\d{9}$/', $mobile))
    return true;
  return false;
}

/**
 * 检查手机号码格式
 * @param $proArr array() 例  $prize_arr =array('a'=>25,'b'=>25,'c'=>50);
 * @return int|string
 */
function get_rand($proArr)
{
  $result = '';
  //概率数组的总概率精度
  $proSum = array_sum($proArr);
  //概率数组循环
  foreach ($proArr as $key => $proCur) {
    $randNum = mt_rand(1, $proSum);             //抽取随机数
    if ($randNum <= $proCur) {
      $result = $key;                         //得出结果
      break;
    } else {
      $proSum -= $proCur;
    }
  }
  unset ($proArr);
  return $result;
}

/**
 * 返回选项值
 * @param $arr
 * @param $num
 * @return mixed|string
 */
function get_option_value($arr = '', $num = 0)
{
  if (is_array($arr)) {
    foreach ($arr as $k => $v) {
      if ($k === $num) {
        return $v;
      }
    }
  } else {
    return $arr;
  }
}

/**
 * 返回选项值
 * @param string $arr
 * @param int $num
 * @return string
 */
function get_props_value($arr = '', $num = 0)
{
  if (is_array($arr)) {
    foreach ($arr as $k => $v) {
      if ($v['id'] == $num) {
        return $v['name'];
      }
    }
  } else {
    return $arr;
  }
}

/**
 * 返回返回二维数组值
 * @param string $arr
 * @param string $str
 * @return array|string
 */
function get_array_value($arr = '', $str = '')
{
  if (is_array($arr)) {
    $data = [];
    foreach ($arr as $k => $v) {
      foreach ($v as $kk => $vv) {
        if ($kk === $str) {
          array_push($data, $vv);
        }
      }
    }
    return $data;
  } else {
    return $arr;
  }
}

/**
 * 功能：计算两个时间戳之间相差的日时分秒
 * @param $begin_time 开始时间戳
 * @param $end_time   结束时间戳
 * @return array
 */
function timediff($begin_time, $end_time)
{
  if ($begin_time < $end_time) {
    $starttime = $begin_time;
    $endtime = $end_time;
  } else {
    $starttime = $end_time;
    $endtime = $begin_time;
  }

  //计算天数
  $timediff = $endtime - $starttime;
  $days = intval($timediff / 86400);
  //计算小时数
  $remain = $timediff % 86400;
  $hours = intval($remain / 3600);
  //计算分钟数
  $remain = $remain % 3600;
  $mins = intval($remain / 60);
  //计算秒数
  $secs = $remain % 60;
  //$res  = array("day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs);
  return $days;
}

/**
 * 将字符串参数变为数组
 * @param $query
 * @return array array (size=10)
 */
function convertUrlQuery($query)
{
  $queryParts = explode('&', $query);

  $params = array();
  foreach ($queryParts as $param) {
    $item = explode('=', $param);
    $params[$item[0]] = $item[1];
  }

  return $params;
}

/**
 * 将参数变为字符串
 * @param $array_query
 * @return string string
 */
function getUrlQuery($array_query)
{
  $tmp = array();
  foreach ($array_query as $k => $param) {
    $tmp[] = $k . '=' . $param;
  }
  $params = implode('&', $tmp);
  return $params;
}

/**
 * 下载文件
 * @param        $file_url  string   下载的文件路径
 * @param string $new_name 下载后的文件名
 */
function download($file_url, $new_name = '')
{
  if (!isset($file_url) || trim($file_url) == '') {
    echo '500';
  }
  if (!file_exists($file_url)) { //检查文件是否存在
    echo '';
  }
  $file_name = basename($file_url);
  $file_type = explode('.', $file_url);
  $file_type = $file_type[count($file_type) - 1];
  $file_name = trim($new_name == '') ? $file_name : urlencode($new_name);
  $file_type = fopen($file_url, 'r'); //打开文件
  //输入文件标签
  header("Content-type: application/octet-stream");
  header("Accept-Ranges: bytes");
  header("Accept-Length: " . filesize($file_url));
  header("Content-Disposition: attachment; filename=" . $file_name);
  //输出文件内容
  echo fread($file_type, filesize($file_url));
  fclose($file_type);
}

/**
 * 是否显示权限按钮
 * @param string $str
 * @return bool
 */
function roleShow($str = '')
{
  $RoleUrl = request()->session()->get('RoleUrl');
  array_push($RoleUrl, 'admin/newload');
  if (in_array($str, $RoleUrl)) {
    return true;
  } else {
    return false;
  }
}

/**
 * 随机字符串生成
 * @param int $len 生成的字符串长度
 * @return string
 */
function random_string($len = 6)
{
  $chars = [
      "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
      "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
      "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
      "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
      "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
      "3", "4", "5", "6", "7", "8", "9"
  ];
  $charsLen = count($chars) - 1;
  shuffle($chars);    // 将数组打乱
  $output = "";
  for ($i = 0; $i < $len; $i++) {
    $output .= $chars[mt_rand(0, $charsLen)];
  }
  return $output;
}

/**
 * 解密用 str_encode加密的字符串
 * @param        $string    要解密的字符串
 * @param string $key 加密时salt
 * @param int $expiry 多少秒后过期
 * @param string $operation 操作,默认为DECODE
 * @return bool|string
 */
function str_decode($string, $key = '', $expiry = 0, $operation = 'DECODE')
{
  $ckey_length = 4;

  $key = md5($key ? $key : config('zhops.admin.security_key'));
  $keya = md5(substr($key, 0, 16));
  $keyb = md5(substr($key, 16, 16));
  $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

  $cryptkey = $keya . md5($keya . $keyc);
  $key_length = strlen($cryptkey);

  $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
  $string_length = strlen($string);

  $result = '';
  $box = range(0, 255);

  $rndkey = [];
  for ($i = 0; $i <= 255; $i++) {
    $rndkey[$i] = ord($cryptkey[$i % $key_length]);
  }

  for ($j = $i = 0; $i < 256; $i++) {
    $j = ($j + $box[$i] + $rndkey[$i]) % 256;
    $tmp = $box[$i];
    $box[$i] = $box[$j];
    $box[$j] = $tmp;
  }

  for ($a = $j = $i = 0; $i < $string_length; $i++) {
    $a = ($a + 1) % 256;
    $j = ($j + $box[$a]) % 256;
    $tmp = $box[$a];
    $box[$a] = $box[$j];
    $box[$j] = $tmp;
    $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
  }

  if ($operation == 'DECODE') {
    if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
      return substr($result, 26);
    } else {
      return '';
    }
  } else {
    return $keyc . str_replace('=', '', base64_encode($result));
  }

}

/**
 * 加密字符串
 * @param        $string 要加密的字符串
 * @param string $key salt
 * @param int $expiry 多少秒后过期
 * @return bool|string
 */
function str_encode($string, $key = '', $expiry = 0)
{
  return str_decode($string, $key, $expiry, "ENCODE");
}

/**
 * 请求curl
 * @param  string $uri 请求的url
 * @param  array $params 参数
 * @param  string $method
 * @return mixed
 */
if (!function_exists('curls')) {
  function curls($uri, $params = [], $method = 'GET')
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    if ('GET' == $method) {
      $uri .= '?' . http_build_query($params);
    } else {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    }
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);     //不直接输出
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }
}

/**
 * get请求curl封装
 * @param $url
 * @return mixed
 */
function curl_get_https($url)
{
  $curl = curl_init(); // 启动一个CURL会话
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_HEADER, 0);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
  // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
  $tmpInfo = curl_exec($curl);     //返回api的json对象
  //关闭URL请求
  curl_close($curl);
  return $tmpInfo;    //返回json对象
}

/**
 * post请求curl封装
 * @param $url
 * @param $data
 * @return mixed
 */
function curl_post_https($url, $data)
{ // 模拟提交数据函数
  $curl = curl_init(); // 启动一个CURL会话
  curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
  curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
  curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
  curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
  curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
  curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
  $tmpInfo = curl_exec($curl); // 执行操作
  if (curl_errno($curl)) {
    echo 'Errno' . curl_error($curl);//捕抓异常
  }
  curl_close($curl); // 关闭CURL会话
  return $tmpInfo; // 返回数据，json格式
}

/**
 * 生成vip激活码
 * @param int $nums 生成多少个优惠码
 * @param array $exist_array 排除指定数组中的优惠码
 * @param int $code_length 生成优惠码的长度
 * @param int $prefix 生成指定前缀
 * @return array                  返回优惠码数组
 */
function generateCode($nums, $exist_array = '', $code_length = 12, $prefix = '')
{
  $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz";
  $promotion_codes = array();//这个数组用来接收生成的优惠码
  for ($j = 0; $j < $nums; $j++) {
    $code = '';
    for ($i = 0; $i < $code_length; $i++) {
      $code .= $characters[mt_rand(0, strlen($characters) - 1)];
    }
    //如果生成的4位随机数不再我们定义的$promotion_codes数组里面
    if (!in_array($code, $promotion_codes)) {
      if (is_array($exist_array)) {
        if (!in_array($code, $exist_array)) {//排除已经使用的优惠码
          $promotion_codes[$j] = $prefix . $code; //将生成的新优惠码赋值给promotion_codes数组
        } else {
          $j--;
        }
      } else {
        $promotion_codes[$j] = $prefix . $code;//将优惠码赋值给数组
      }
    } else {
      $j--;
    }
  }
  return $promotion_codes;
}

/**
 * 获取文件后缀
 * @param $filename
 * @return string
 */
function getSuffix($filename)
{
  try {
    $ext = substr(strrchr($filename, '.'), 1, strlen(strrchr($filename, '.')));
    return $ext;
  } catch (Exception $e) {
    return '';
  }

}

/**
 * 切分SQL文件成多个可以单独执行的sql语句
 * @param        $file            string sql文件路径
 * @param        $tablePre        string 表前缀
 * @param string $charset 字符集
 * @param string $defaultTablePre 默认表前缀
 * @param string $defaultCharset 默认字符集
 * @return array
 */
function split_sql($file, $charset = 'utf8', $defaultCharset = 'utf8')
{
  if (file_exists($file)) {
    //读取SQL文件
    $sql = file_get_contents($file);
    $sql = str_replace("\r", "\n", $sql);
    $sql = str_replace("BEGIN;\n", '', $sql);//兼容 navicat 导出的 insert 语句
    $sql = str_replace("COMMIT;\n", '', $sql);//兼容 navicat 导出的 insert 语句
    $sql = str_replace($defaultCharset, $charset, $sql);
    $sql = trim($sql);
    $sqls = explode(";\n", $sql);
    return $sqls;
  }

  return [];
}

/**
 * @param     $db
 * @param     $data
 * @param     $name
 * @param int $type 类型1:读取sql文件数据插入 类型2:定义二维数组插入数据 类型3:定义一维数组插入数据
 */
function execute_sql($db, $data, $name = '', $type = 1)
{
  try {
    $exists = $db->exists();
    if (!$exists) {
      switch ($type) { //读取sql文件里面的数据
        case 1:
          //开启事务
          DB::beginTransaction();
          foreach ($data as $v) {
            $res = DB::insert($v);
            if (!$res) {
              DB::rollBack();//事务回滚
              echo '数据库表' . $name . ':迁移失败' . PHP_EOL;
              exit();
            }
          }
          DB::commit();
          echo '数据库表' . $name . ':迁移成功' . PHP_EOL;
          break;
        case 2: //批量插入二维数据
          $res = $db->insertGetId($data);
          if ($res) {
            echo '数据库表' . $name . ':迁移成功' . PHP_EOL;
          } else {
            echo '数据库表' . $name . ':迁移失败' . PHP_EOL;
          }
          break;
        case 3:
          $res = $db->insert($data);//批量插入一维数据
          if ($res) {
            echo '数据库表' . $name . ':迁移成功' . PHP_EOL;
          } else {
            echo '数据库表' . $name . ':迁移失败' . PHP_EOL;
          }
          break;
      }
    } else {
      echo '数据库表' . $name . ':已有数据不需要迁移' . PHP_EOL;
    }
  } catch (\Exception $e) {
    echo '数据库表' . $name . ':迁移异常' . PHP_EOL;
  }

}

/**
 * @param String $var 要查找的变量
 * @param Array $scope 要搜寻的范围
 * @param String        变量名称
 */
function get_variable_name(&$var, $scope = null)
{

  try {
    $scope = $scope == null ? $GLOBALS : $scope; // 如果没有范围则在globals中找寻
    $tmp = $var;
    $var = 'tmp_value_' . mt_rand();
    $name = array_search($var, $scope, true); // 根据值查找变量名称
    $var = $tmp;
    return $name;
  } catch (\Exception $e) {
    return '';
  }

}

/**
 * 执行sql文件更新
 * @param        $file
 * @param string $name
 */
function update_execute_sql($file, $name = '')
{
  $path = pathinfo($file)['dirname'];
  $filename = pathinfo($file)['filename'];
  $extension = pathinfo($file)['extension'];
  $lock = $path . '/' . $filename . '.lock';
  $exists = file_exists($lock);

  try {
    if (!$exists) {
      $data = split_sql($file);
      //开启事务
      DB::beginTransaction();
      foreach ($data as $v) {
        $res = DB::insert($v);
        if (!$res) {
          DB::rollBack();//事务回滚
          echo $filename . '.' . $extension . '-文件-后台模块' . $name . ':更新失败' . PHP_EOL;
          exit();
        }
      }
      DB::commit();
      //生成更新锁
      fopen($lock, 'w');
      echo $filename . '.' . $extension . '-文件-后台模块' . $name . ':更新成功' . PHP_EOL;
    } else {
      echo $filename . '.' . $extension . '-文件-后台模块' . $name . ':更新已更新,不需要更新' . PHP_EOL;
    }
  } catch (\Exception $e) {
    echo $filename . '.' . $extension . '-文件-后台模块' . $name . ':数据更新异常' . PHP_EOL;
  }

}

/**
 * 返回数组名称值
 * @param $arr1
 * @param $arr2
 */
function check_arr_value($arr1, $arr2)
{
  try {
    $arr3 = [];
    foreach ($arr1 as $v) {
      foreach ($arr2 as $vv) {
        if ($v == $vv['id']) {
          $arr3[] = $vv['name'];
        }
      }

    }
    return implode(',', $arr3);
  } catch (\Exception $e) {
    return '未知';
  }

}


/**
 * 获取文件相对路径
 * @param string $assetUrl 文件的URL
 * @return string
 */
function asset_relative_url($assetUrl)
{
  $domain = $_SERVER['HTTP_HOST'];
  if (preg_match("/^\/upload\//", $assetUrl)) {
    $assetUrl = preg_replace("/^\/upload\//", '', $assetUrl);
  } elseif (preg_match("/^http(s)?:\/\/$domain\/upload\//", $assetUrl)) {
    $assetUrl= preg_replace("/^http(s)?:\/\/$domain\/upload\//", '', $assetUrl);
  }
  return $assetUrl;
}


/**
 * 返回带协议的域名
 */
function get_domain()
{
  return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
}


/**
 * 替换编辑器内容中的文件地址
 * @param string $content 编辑器内容
 * @param boolean $isForDbSave true:表示把绝对地址换成相对地址,用于数据库保存,false:表示把相对地址换成绝对地址用于界面显示
 * @return string
 */
function replace_content_file_url($content, $isForDbSave = false)
{

  include_once(__DIR__ . '/Common/phpQuery/phpQuery.php');

  \phpQuery::newDocumentHTML($content);
  $pq = pq(null);

  $localStorage = new Local([]);

  $domain = $_SERVER['HTTP_HOST'];

  $images = $pq->find("img");

  if ($images->length) {
    foreach ($images as $img) {
      $img = pq($img);
      $imgSrc = $img->attr("src");

      if ($isForDbSave) {
        if (preg_match("/^\/upload\//", $imgSrc)) {
          $img->attr("src", preg_replace("/^\/upload\//", '', $imgSrc));
        } elseif (preg_match("/^http(s)?:\/\/$domain\/upload\//", $imgSrc)) {
          $img->attr("src", $localStorage->getFilePath($imgSrc));
        }
      } else {
        $newImgSrc = get_domain() . '/upload/' . $imgSrc;
        $img->attr("src", $newImgSrc);
      }


    }
  }

  $links = $pq->find("a");
  if ($links->length) {
    foreach ($links as $link) {
      $link = pq($link);
      $href = $link->attr("href");

      if ($isForDbSave) {
        if (preg_match("/^\/upload\//", $href)) {
          $link->attr("href", preg_replace("/^\/upload\//", '', $href));
        } elseif (preg_match("/^http(s)?:\/\/$domain\/upload\//", $href)) {
          $link->attr("href", $localStorage->getFilePath($href));
        }

      } else {
        if (!(preg_match("/^\//", $href) || preg_match("/^http/", $href))) {

          $href = get_domain() . '/upload/' . $href;
          $link->attr("href", $href);
        }

      }

    }
  }

  $content = $pq->html();

  \phpQuery::$documents = null;


  return $content;

}


/**
 * 创建上传文件
 * @param $file
 * @param $content
 */
function create_upload_file($file = '', $content = '')
{
  try {
    $fileData = explode('/', $file);

    $fileDir = $fileData[0];
    $fileName = $fileData[1];

    //目录判断是否存在
    $exists = Storage::disk('upload')->exists($fileDir);

    //目录不存在创建目录
    if (!$exists) {
      Storage::disk('upload')->makeDirectory($fileDir);
    }

    //写入上传文件
    $fileExists = Storage::disk('upload')->exists($file);
    if (!$fileExists) {
      Storage::disk('upload')->put($fileDir . '/' . $fileName, $content);
    }

  } catch (\Exception $e) {

  }

}

/**
 * 删除上传文件
 * @param $file
 * @param $content
 */
function del_upload_file($file)
{
  try {
    $fileData = explode('/', $file);
    $fileDir = $fileData[0];
    //删除上传文件
    $fileExists = Storage::disk('upload')->exists($file);
    if ($fileExists) {
      Storage::disk('upload')->delete($file);
      //删除文件后 文件夹为空删除目录
      $files = Storage::disk('upload')->files($fileDir);
      if (empty($files)) {
        Storage::disk('upload')->deleteDirectory($fileDir);
      }
    }
  } catch (\Exception $e) {

  }
}


/**
 * 正则提取 src地址
 * @param $str
 */
function get_html_src($str)
{

  try {
    $preg = '/<.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
    $domain = $_SERVER['HTTP_HOST'];
    preg_match_all($preg, $str, $strArr);

    if (!empty($strArr)) {
      $strArr = $strArr[1];

      foreach ($strArr as $k => $v) {
        if (preg_match("/^\/upload\//", $v)) {
          $strArr[$k] = preg_replace("/^\/upload\//", '', $v);
        } elseif (preg_match("/^http(s)?:\/\/$domain\/upload\//", $v)) {
          $strArr[$k] = preg_replace("/^http(s)?:\/\/$domain\/upload\//", '', $v);
        }
      }
    }
    return $strArr;
  } catch (\Exception $e) {
    return [];
  }

}

/**
 * 删除文章图片资源
 * @param string $content
 * @param string $content_o
 * @param int $type 1为更新文章内容 2为 删除整个文章
 */
function del_upload_files($content='',$content_o='',$type=1){
    try{
      switch ($type){
        case 1:
          $arr =  get_html_src($content);
          $arr_o = get_html_src($content_o);
          if(!empty($arr)&&!empty($arr_o)){
            foreach ($arr as $v){
              if(!in_array($v,$arr_o)){
                del_upload_file($v);
              }
            }
          }
          break;
        case 2:
          //删除内容 删除缩略图
          $arr =  get_html_src($content);
          if(!empty($arr)){
            foreach ($arr as $v){
              del_upload_file($v);
            }
          }
          if(!empty($content_o)){
            $content_o = asset_relative_url($content_o);
            del_upload_file($content_o);
          }
          break;
      }
    }catch (\Exception $e){
    }
}
