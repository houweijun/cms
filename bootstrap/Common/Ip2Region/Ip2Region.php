<?php
// +----------------------------------------------------------------------
// | Zhihuo [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 zhihuo All rights reserved.
// +----------------------------------------------------------------------
// | Author: liuxiaojin <935876982@qq.com>
// +----------------------------------------------------------------------

namespace Bootstrap\Common\Ip2Region;


defined('INDEX_BLOCK_LENGTH') or define('INDEX_BLOCK_LENGTH', 12);
defined('TOTAL_HEADER_LENGTH') or define('TOTAL_HEADER_LENGTH', 4096);

class Ip2Region
{
    /**
     * db file handler
     */
    private $dbFileHandler = NULL;

    /**
     * header block info
     */
    private $HeaderSip = NULL;
    private $HeaderPtr = NULL;
    private $headerLen = 0;

    /**
     * super block index info
     */
    private $firstIndexPtr = 0;
    private $lastIndexPtr = 0;
    private $totalBlocks = 0;

    /**
     * for memory mode only
     *  the original db binary string
     */
    private $dbBinStr = NULL;
    private $dbFile = NULL;

    /**
     * construct method
     *
     * @param    ip2regionFile
     */
    public function __construct($ip2regionFile = false)
    {
        $this->dbFile = $ip2regionFile === false ? __DIR__ . '/ip2region.db' : $ip2regionFile;
    }

    /**
     * all the db binary string will be loaded into memory
     * then search the memory only and this will a lot faster than disk base search
     * @Note:
     * invoke it once before put it to public invoke could make it thread safe
     *
     * @param   $ip
     */
    public function memorySearch($ip)
    {

        try {
            //check and load the binary string for the first time
            if ($this->dbBinStr == NULL) {
                $this->dbBinStr = file_get_contents($this->dbFile);
                if ($this->dbBinStr == false) {
                    return [
                        'provice' => '未知',
                        'city'    => '未知',
                        'isp'     => '其他'
                    ];
                }

                $this->firstIndexPtr = self::getLong($this->dbBinStr, 0);
                $this->lastIndexPtr  = self::getLong($this->dbBinStr, 4);
                $this->totalBlocks   = ($this->lastIndexPtr - $this->firstIndexPtr) / INDEX_BLOCK_LENGTH + 1;
            }

            if (is_string($ip)) $ip = self::safeIp2long($ip);

            //binary search to define the data
            $l       = 0;
            $h       = $this->totalBlocks;
            $dataPtr = 0;
            while ($l <= $h) {
                $m   = (($l + $h) >> 1);
                $p   = $this->firstIndexPtr + $m * INDEX_BLOCK_LENGTH;
                $sip = self::getLong($this->dbBinStr, $p);
                if ($ip < $sip) {
                    $h = $m - 1;
                } else {
                    $eip = self::getLong($this->dbBinStr, $p + 4);
                    if ($ip > $eip) {
                        $l = $m + 1;
                    } else {
                        $dataPtr = self::getLong($this->dbBinStr, $p + 8);
                        break;
                    }
                }
            }

            //not matched just stop it here
            if ($dataPtr == 0) return NULL;

            //get the data
            $dataLen = (($dataPtr >> 24) & 0xFF);
            $dataPtr = ($dataPtr & 0x00FFFFFF);

            $region     = substr($this->dbBinStr, $dataPtr + 4, $dataLen - 4);
            $regionData = explode("|", $region);
            return [
                'provice' => $regionData[2] == '0' ? '内网IP' : $regionData[2],
                'city'    => $regionData[3] == '0' ? '内网IP' : $regionData[3],
                'isp'     => $regionData[4] == '内网IP' ? '其他' : $regionData[4]
            ];
        } catch (\Exception $e) {
            return [
                'provice' => '未知',
                'city'    => '未知',
                'isp'     => '其他'
            ];
        }

    }

    /**
     * get the data block throught the specifield ip address or long ip numeric with binary search algorithm
     *
     * @param    ip
     * @return    mixed Array or NULL for any error
     */
    public function binarySearch($ip)
    {
        try {
            //check and conver the ip address
            if (is_string($ip)) $ip = self::safeIp2long($ip);
            if ($this->totalBlocks == 0) {
                //check and open the original db file
                if ($this->dbFileHandler == NULL) {
                    $this->dbFileHandler = fopen($this->dbFile, 'r');
                    if ($this->dbFileHandler == false) {
                        return [
                            'city'    => '未知',
                            'provice' => '未知',
                            'isp'     => '其他'
                        ];
                    }
                }

                fseek($this->dbFileHandler, 0);
                $superBlock = fread($this->dbFileHandler, 8);

                $this->firstIndexPtr = self::getLong($superBlock, 0);
                $this->lastIndexPtr  = self::getLong($superBlock, 4);
                $this->totalBlocks   = ($this->lastIndexPtr - $this->firstIndexPtr) / INDEX_BLOCK_LENGTH + 1;
            }

            //binary search to define the data
            $l       = 0;
            $h       = $this->totalBlocks;
            $dataPtr = 0;
            while ($l <= $h) {
                $m = (($l + $h) >> 1);
                $p = $m * INDEX_BLOCK_LENGTH;

                fseek($this->dbFileHandler, $this->firstIndexPtr + $p);
                $buffer = fread($this->dbFileHandler, INDEX_BLOCK_LENGTH);
                $sip    = self::getLong($buffer, 0);
                if ($ip < $sip) {
                    $h = $m - 1;
                } else {
                    $eip = self::getLong($buffer, 4);
                    if ($ip > $eip) {
                        $l = $m + 1;
                    } else {
                        $dataPtr = self::getLong($buffer, 8);
                        break;
                    }
                }
            }

            //not matched just stop it here
            if ($dataPtr == 0) return NULL;


            //get the data
            $dataLen = (($dataPtr >> 24) & 0xFF);
            $dataPtr = ($dataPtr & 0x00FFFFFF);

            fseek($this->dbFileHandler, $dataPtr);
            $data = fread($this->dbFileHandler, $dataLen);

            $region     = substr($data, 4);
            $regionData = explode("|", $region);

            return [
                'provice' => $regionData[2] == '0' ? '内网IP' : $regionData[2],
                'city'    => $regionData[3] == '0' ? '内网IP' : $regionData[3],
                'isp'     => $regionData[4] == '内网IP' ? '其他' : $regionData[4]
            ];
        } catch (\Exception $e) {
            return [
                'provice' => '未知',
                'city'    => '未知',
                'isp'     => '其他'
            ];
        }
    }


    /**
     * safe self::safeIp2long function
     *
     * @param ip
     * */
    public static function safeIp2long($ip)
    {
        $ip = ip2long($ip);

        // convert signed int to unsigned int if on 32 bit operating system
        if ($ip < 0 && PHP_INT_SIZE == 4) {
            $ip = sprintf("%u", $ip);
        }

        return $ip;
    }


    /**
     * read a long from a byte buffer
     *
     * @param    b
     * @param    offset
     */
    public static function getLong($b, $offset)
    {
        $val = (
            (ord($b[$offset++])) |
            (ord($b[$offset++]) << 8) |
            (ord($b[$offset++]) << 16) |
            (ord($b[$offset]) << 24)
        );

        // convert signed int to unsigned int if on 32 bit operating system
        if ($val < 0 && PHP_INT_SIZE == 4) {
            $val = sprintf("%u", $val);
        }

        return $val;
    }

    /**
     * destruct method, resource destroy
     */
    public function __destruct()
    {
        if ($this->dbFileHandler != NULL) {
            fclose($this->dbFileHandler);
        }

        $this->dbBinStr  = NULL;
        $this->HeaderSip = NULL;
        $this->HeaderPtr = NULL;
    }
}