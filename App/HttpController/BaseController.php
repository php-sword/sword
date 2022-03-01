<?php declare(strict_types=1);
namespace App\HttpController;

use EasySwoole\Component\Context\ContextManager;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\RedisPool\RedisPool;
use EasySwoole\Template\Render;
use Sword\Component\Session\Session;

/**
 * Http基类控制器，并提供一些常用功能
 */
class BaseController extends Controller
{

    //输出内容
    protected function write(...$opt)
    {
        $this->response()->write(...$opt);
        return null;
    }

    //获取请求参数
    protected function input(string $type = '')
    {
        $type = strtoupper($type);
        $req = $this->request();
        if($type == 'GET'){
            return $req->getQueryParams();
        }elseif ($type == 'POST') {
            return $req->getParsedBody();
        }else{
            return $req->getRequestParam();
        }
    }

    //获取Session对象
    protected function session(string $key, $data = SWORD_NULL)
    {
        $sessionId = $this->sessionId();
        $session = Session::getInstance()->create($sessionId);
        if($data === SWORD_NULL){
            if($d = $session->get($key)){
                return $d;
            }else{
                return null;
            }
        }elseif($data == null){
            $session->del($key);
        }else{
            $session->set($key, $data);
        }
        return null;
    }

    //获取SessionId
    protected function sessionId()
    {
        return $this->request()->getAttribute('sessionId');
    }

    //添加数据参数
    protected function assign(...$opt): void
    {
        if(empty($opt)) return;
        $context = ContextManager::getInstance();
        $assignData = $context->get('assign');

        if(is_array($opt[0])){
            //是数组，合并数组
            $assignData = array_merge($assignData, $opt[0]);
        }else if(is_string($opt[0])){
            //字符串，键对
            $assignData[$opt[0]] = $opt[1];
        }

        ContextManager::getInstance()->set('assign', $assignData);
    }

    //输出渲染模板
    protected function fetch(string $name, array $param = [], string $type = 'raw'): void
    {
        if($type == 'think'){
            $context = ContextManager::getInstance();
            $assignData = $context->get('assign');

            //判断是否已有参数
            if($assignData){
                //合并数据
                $param = array_merge($assignData, $param);
            }
            //ThinkPHP模板 渲染输出
            $this->response()->write(Render::getInstance()->render($name,$param));
        }elseif($type == 'raw'){
            //直接原文输出
            $conf = config('view');
            $file = $conf['view_path'] . $name . '.' .$conf['view_suffix'] ;
            $this->response()->write(file_get_contents($file));
        }
    }

    //方法不存在错误响应 400页面
    protected function actionNotFound(?string $action)
    {
        $accept = $this->request()->getHeader('accept');
        if($accept and strstr($accept[0], 'html')){
            $response = $this->response();
            $response->withStatus(400);
            $file = ROOT_PATH. "/Public/400.html";
            if(is_file($file)){
                $response->write(file_get_contents($file));
            }else{
                $response->write('400 Bad Request');
            }
        }else{
            $this->withData(400, '400 Bad Request');
        }
        return null;
    }

    /**
     * api接口返回数据，封装统一规则
     * @param int $code 错误代码，0为无错误
     * @param string $msg 响应提示文本
     * @param array|object $result 响应数据主体
     * @param int $count 统计数量，用于列表分页
     * @return null
     */
    protected function withData(int $code = 0, string $msg = '', $result = [], int $count = -1)
    {
        $context = ContextManager::getInstance();
        $assignData = $context->get('assign');
        if($assignData and is_array($result)){
            $result = array_merge($assignData, $result);
        }

        $ret = [
            'status' => $code === 0?1:0,
            'code'   => $code,
            'result'   => $result,
            'message'=> $msg
        ];
        if($count >= 0) $ret['count'] = $count;

        $this->response()->withHeader('Content-type','application/json;charset=utf-8');
        $this->response()->withHeader('Access-Control-Allow-Origin','*');
        $this->write(json_encode($ret, JSON_UNESCAPED_UNICODE));

        return null;
    }

    /**
     * 表单提交安全锁
     * @param string $key
     * @param int|null $expire 自动解锁时间，若为null则立即解锁
     * @return bool 是否正常上锁
     * @throws \EasySwoole\Redis\Exception\RedisException
     */
    protected function formLock(string $key, ?int $expire = 1): ?bool
    {
        $redis = RedisPool::defer();
        $key = 'form_lock:'.$key;
        if($expire == null){
            $redis->del($key);
            return true;
        }
        return $redis->set($key, 1, ['NX', 'EX' => $expire]);
    }

//     function onException(\Throwable $throwable): void
//     {
//         //直接给前端响应500并输出系统错误
//         $this->response()->withStatus(Status::CODE_INTERNAL_SERVER_ERROR);
//         $this->response()->write('系统繁忙,请稍后再试 ');
//     }
}