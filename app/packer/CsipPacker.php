<?php
declare(strict_types=1);
/**
 * This file is part of Sword.
 * @link     http://sword.kyour.cn
 * @document http://sword.kyour.cn/doc
 * @contact  kyour@vip.qq.com
 * @license  http://github.com/php-sword/sword/blob/master/LICENSE
 */

namespace app\packer;
/**
 * Class CsipPacker
 *
 * Csip协议封包类
 */
class CsipPacker
{
    public const TYPE = 'CSIP';
    
    //协议数据
    
    //功能码
    public $funcCode = '0000';
    //数据长度
    public $dataLen = 0;
    //参数长度
    public $paramLen = 0;
    
    //主体参数
    public $bodyParam = [];
    //主体数据
    public $bodyData = '';

    /**
     * @return string
     */
    public static function getType(): string
    {
        return self::TYPE;
    }

    //验证包是否为本协议格式
    public static function verify(string $data) :bool
    {
        return substr($data,0,4) == self::TYPE;
    }

    //解包
    public function decode(string $data) :CsipPacker
    {
        //取出功能码
        $this->funcCode = substr($data,4,4);

        //取出数据长度
        $this->dataLen = (int) substr($data,8,6);
        
        //取出参数长度
        $this->paramLen = (int) substr($data,15,4);

        //数据参数
        parse_str(substr($data,19,$this->paramLen - 1),$_parmArr);
        $this->bodyParam = $_parmArr;

        //数据
        $this->bodyData = substr($data, 19 + $this->paramLen, $this->dataLen - $this->paramLen - 4);

        return $this;
    }

    //封包
    public function encode() :string
    {
        $packstr = self::TYPE.$this->funcCode;
        $param = urldecode(http_build_query($this->bodyParam)) . "|";
        $len = mb_strlen($this->bodyData) + mb_strlen($param) + 4;
        $len_str = substr('000000'.$len,-6);

        $param_len = substr('0000'.mb_strlen($param),-4);

        $packstr .= $len_str . "|" . $param_len . $param . $this->bodyData . "\r\n";

        return $packstr;
    }
}