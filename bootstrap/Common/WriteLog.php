<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/28
 * Time: 16:47
 */

namespace Bootstrap\Common;

use Illuminate\Support\Facades\Storage;

class WriteLog
{
    /**
     * 写钻石日志
     *
     * @param      $user_id   int           用户id
     * @param      $content   string        写入的内容
     * @param      $num       int           钻石数量
     * @param bool $filename  日志名称
     */
    static public function writeDiamond($user_id, $content, $num, $filename = false)
    {
        $date  = date('Y-m-d H:i:s', time());
        $date1 = date('Y-m-d');
        if ($filename === false) {
            $fliename = $date1 . 'zuanshi' . '.doc';
        }
        //将内容写入文件日志
        Storage::disk('currency')->append($fliename, $date . ' 用户(ID:' . $user_id . ')' . $content . '(' . $num . ')' . "<br>");
    }

    /**
     * 写积分日志
     *
     * @param      $user_id   int      用户id
     * @param      $content   string   写入的内容
     * @param      $num       int      积分数量
     * @param bool $filename  日志名称
     */
    static public function writeIntgral($user_id, $content, $num, $filename = false)
    {
        $date  = date('Y-m-d H:i:s', time());
        $date1 = date('Y-m-d');
        if ($filename === false) {
            $fliename = $date1 . 'jifen' . '.doc';
        }
        //将内容写入文件日志
        Storage::disk('integral')->append($fliename, $date . ' 用户(ID:' . $user_id . ')' . $content . '(' . $num . ')' . "<br>");
    }

    /**
     * 领取道具日志
     *
     * @param      $user_id   int      用户id
     * @param      $content   string   写入的内容
     * @param      $num       int      道具数量
     * @param bool $filename  日志名称
     */
    static public function writeProps($user_id, $content, $num, $filename = false)
    {
        $date  = date('Y-m-d H:i:s', time());
        $date1 = date('Y-m-d');
        if ($filename === false) {
            $fliename = $date1 . 'getProps' . '.log';
        }
        //将内容写入文件日志
        Storage::disk('getRecord')->append($fliename, $date . ' 用户(ID:' . $user_id . ')' . $content . '(' . $num . ')' . "<br>");
    }

    /**
     * 充值 写入日志文件
     * @param     $user_id
     * @param     $amount
     * @param int $type
     * @param int $res_type
     */
    static public function writePay($user_id, $amount, $type = 1, $res_type = 1)
    {
        $date  = date('Y-m-d H:i:s', time());
        $date1 = date('Y-m-d');
        global $file, $filename;
        switch ($res_type) {
            case 1:
                $file     = 'successPay';
                $filename = $date1 . '-' . $file . '.doc';
                break;
            case 2:
                $file = 'errorPay';
                $filename = $date1 . '-' . $file . '.doc';
                break;
        }
        switch ($type) {
            case 1:
                //将内容写入文件日志
                $content = '微信充值';
                Storage::disk($file)->append($filename, $date . ' 用户(ID:' . $user_id . ')' . $content . '(' . $amount . ')' . "<br>");
                break;
            case 2:
                //将内容写入文件日志
                $content = '支付宝充值';
                Storage::disk($file)->append($filename, $date . ' 用户(ID:' . $user_id . ')' . $content . '(' . $amount . ')' . "<br>");
                break;
            case 3:
                //将内容写入文件日志
                $content = '银联充值';
                Storage::disk($file)->append($filename, $date . ' 用户(ID:' . $user_id . ')' . $content . '(' . $amount . ')' . "<br>");
                break;
        }
    }

    /**
     * 各种虚拟道具的消费记录
     *
     * @param      $user_id   int      用户id
     * @param      $content   string   写入的内容
     * @param      $num       int      道具数量
     */
    static public function writeVirProps($user_id, $content, $num)
    {
        $date     = date('Y-m-d H:i:s',time());
        $fliename = 'V'.$user_id . '.log';
        //将每个用户虚拟道具内容写入自己的道具文件日志
        Storage::disk('virtualpacks')->append($fliename, $date . ' 用户(ID:' . $user_id . ')' . $content . '(' . $num . ')' . "<br>");
        //将虚拟日志的改动记录到每天虚拟日志里
        $filename1 = date('Y-m-d').'virprops.doc';
        Storage::disk('virtualpacks')->append($filename1, $date . ' 用户(ID:' . $user_id . ')' . $content . '(' . $num . ')' . "<br>");
    }

    /**
     * 实体道具记录
     *
     * @param      $user_id   int      用户id
     * @param      $content   string   写入的内容
     * @param      $num       int      道具数量
     */
    static public function writeEnProps($user_id, $content, $num, $time)
    {
        $date     = date('Y-m-d H:i:s',$time);
        $stime    = date('Y-m-d',$time);
        $filename = $stime.'enprops.doc';
        //将每个用户虚拟道具内容写入自己的道具文件日志
        Storage::disk('getRecord')->append($filename, $date . ' 用户(ID:' . $user_id . ')' . $content . '(' . $num . ')' . "<br>");
    }

    /**
     * qq登录错误日志
     *
     * @param      $user_id   int      用户id
     * @param      $content   string   写入的内容
     * @param      $num       int      道具数量
     */
    static public function writeLoginQqE($user_id,$content, $time)
    {
        $date     = date('Y-m-d H:i:s',$time);
        $stime    = date('Y-m-d',$time);
        $filename = $stime.'errorqq.doc';
        //将每个用户虚拟道具内容写入自己的道具文件日志
        Storage::disk('errorQqLogin')->append($filename, $date  . ' 用户(ID:' . $user_id . ')' .$content  . "<br>");
    }
}