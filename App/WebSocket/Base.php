<?php declare(strict_types=1);
namespace App\WebSocket;

use EasySwoole\Socket\AbstractInterface\Controller;

/**
 * Class BaseSocket
 *
 * 此类是websocket控制器基类
 *
 * @package App\WebSocket
 */
class Base extends Controller
{

    /**
     * Session操作
     * @param string $key
     * @param mixed $data
     * @return bool|mixed|null
     * @throws \EasySwoole\Redis\Exception\RedisException
     */
    public function session(string $key = SWORD_NULL, $data = SWORD_NULL)
    {
        $args = $this->caller()->getArgs();

        $name = config('session.session_name');
        if(empty($args[$name])){
            return false;
        }

        if(empty($args[$name])) return false;

        $token = $args[$name];

        $value = cache($token);
        if(!$value) return false;

        if($key == SWORD_NULL){
            return $value;
        }elseif($data == SWORD_NULL){
            return $value[$key] ?? null;
        }elseif($data == null){
            unset($value[$key]);
            cache($token, $value);
        }else{
            $value[$key] = $data;
            cache($token, $value);
        }
        return true;
    }

    /**
     * 获取sessionId
     * @return bool|string
     */
    public function sessionId()
    {
        $args = $this->caller()->getArgs();

        $name = config('session.session_name');
        if(empty($args[$name])){
            return false;
        }
        return $args[$name];
    }

    /**
     * api接口返回数据，封装统一规则
     * @param int $code 错误代码，0为无错误
     * @param string $msg 响应提示文本
     * @param array|object $result 响应数据主体
     * @param int $count 统计数量，用于列表分页
     * @return bool
     */
    public function withData(int $code = 0, string $msg = '', $result = [], int $count = -1): bool
    {
        $ret = [
            'status' => $code?0:1,
            'code'   => $code,
            'result'   => $result,
            'message'=> $msg
        ];

        if($count >= 0) $ret['count'] = $count;

        //判断是否存在ajax的token
        $args = $this->caller()->getArgs();
        if(!empty($args['ES_TOKEN'])){
            $ret['ES_TOKEN'] = $args['ES_TOKEN'];
        }

        $this->responseImmediately(json_encode($ret));
        return true;
    }

    /**
     * 获取上传数据
     * @return array
     */
    public function input(): array
    {
        $args = $this->caller()->getArgs();
        return $args['data']??[];
    }

}
