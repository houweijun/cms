<?php
namespace Bootstrap\Common;

use Illuminate\Support\Facades\Redis;

class Result
{
    //定义属性
    public $status;     //设置返回状态值
    public $message;    //设置返回信息

    public function toJson()
    {
        return json_encode($this,JSON_UNESCAPED_UNICODE);
    }

    /**
     * 给用户添加钻石到redis里面
     * $userId int   用户id
     * $num    int   添加钻石的数量
     * $type   bool   true：赠送  false:充值
     * return  bool
    */
    public function setNumerical($userId, $num, $type = true)
    {
       $token = getToken($userId);
       $re    = Redis::get($token);
       if($re === null){
           return false;    //该用户token  不存在  也就是  登录失效状态
       }else{
           $userInfo = json_decode($re,true);
           if($type){
              $userInfo['diamond_handsel']  = $userInfo['diamond_handsel'] + $num;
           }else{
               $userInfo['diamond_recharge'] = $userInfo['diamond_recharge'] + $num;
           }
           Redis::set($token,json_encode($userInfo,JSON_UNESCAPED_UNICODE));
           $time = 3600 * 24 * 3;
           Redis::expire($token,$time);
//           Redis::Publish('diamond', $userId);     //广播通知 redis钻石有更新
           return true;
       }
    }

    /**
     * 给用户减少钻石到redis里面
     * $userId int   用户id
     * $num    int   添加钻石的数量
     * return bool
     */
    public function reduceDiamond($userId, $num)
    {
        $token = getToken($userId);
        $re    = Redis::get($token);
        if($re === null){
            return false;    //该用户token  不存在  也就是  登录失效状态
        }else{
            $userInfo = json_decode($re,true);
                $dnum = $userInfo['diamond_handsel'] - $num;
                if($dnum >= 0){
                    $userInfo['diamond_handsel'] = $dnum;
                    Redis::set($token,json_encode($userInfo,JSON_UNESCAPED_UNICODE));
                    $time = 3600 * 24 * 3;
                    Redis::expire($token,$time);
                }else{
                    $userInfo['diamond_handsel'] = 0;
                    $mnum = $userInfo['diamond_recharge'] + $num;
                    if($mnum >= 0){
                        $userInfo['diamond_recharge'] = $mnum;
                    }else{
                        $userInfo['diamond_recharge'] = 0;
                    }
                    Redis::set($token,json_encode($userInfo,JSON_UNESCAPED_UNICODE));
                    Redis::expire($token,24*3600*3);
                }
//            Redis::Publish('diamond', $userId);     //广播通知 redis钻石有更新
            return true;
        }
    }

    /**
     * 判断用户是否登录状态
     * @param $user_id  int   用户id
     * @return bool
     */
    public function verifyLogin($user_id = null)
    {
        if(!isset($_GET['token'])){
            return false;
        }
        $token   = $_GET['token'];
        if($token === 'null'){     //判断是否是游客
            return true;
        }
        $re = Redis::get($token);
        if($re === null){
            return false;    //该用户token  不存在  也就是  登录失效状态
        }
        return true;
    }

    /**
     * 返回json数据
     * @param int $status  返回状态
     * @param (array string) $msg  返回信息
     * @return json
     */
    public function json_encode($status, $msg)
    {
        $arr['status']  = $status;
        $arr['message'] = $msg;
        $json           = json_encode($arr,JSON_UNESCAPED_UNICODE);
        return $json;
    }
}