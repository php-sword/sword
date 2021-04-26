<?php declare(strict_types=1);
namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\RedisPool\RedisPool;
use EasySwoole\Template\Render;

/**
 * 继承Http控制器，并提供常用功能
 */
class BaseController extends Controller
{

    //模板渲染参数
    private $assignData = [];

    //输出内容
    public function write(...$opt)
    {
        $this->response()->write(...$opt);
        return null;
    }

    //获取请求参数
    public function input(string $type = '')
    {
        $req = $this->request();
        if($type == 'get'){
            return $req->getQueryParams();
        }elseif ($type == 'post') {
            return $req->getParsedBody();
        }else{
            return $req->getRequestParam();
        }
    }

    //获取Session对象
    public function session(string $key, $data = SWORD_NULL)
    {
//        $sessionName = config('session.sessionName');
        $sessionId = $this->request()->getAttribute('sessionId');
        $session = \Sword\Component\Session\Session::getInstance()->create($sessionId);

        if($data === SWORD_NULL){
            $d = $session->get($key);
            if($d){
                return $d;
            }else{
                return null;
            }
        }elseif($data == null){
            $session->del($key);
        }else{
            $session->set($key, $data);
        }
    }

    //获取SessionId
    public function sessionId()
    {
        $session_conf = config('session');
        $request = $this->request();
        return $request->getCookieParams($session_conf['sessionName']);
    }

    //添加模板渲染参数
    public function assign(...$opt): void
    {
        if(empty($opt)) return;

        if(is_array($opt[0])){
            //是数组，合并数组
            $this->assignData = array_merge($this->assignData, $opt[0]);
        }else if(is_string($opt[0])){
            //字符串，键对
            $this->assignData[$opt[0]] = $opt[1];
        }
    }

    //输出渲染模板
    public function fetch(string $name, array $param = [], string $type = 'raw'): void
    {
        if($type == 'think'){
            //判断是否已有参数
            if($this->assignData){
                //合并数据
                $param = array_merge($this->assignData, $param);
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

    //方法不存在报错 404页面
    protected function actionNotFound(?string $action)
    {
        $this->response()->withStatus(404);
        // $file = EASYSWOOLE_ROOT.'/vendor/easyswoole/easyswoole/src/Resource/Http/404.html';
        // if(!is_file($file)){
        //     $file = EASYSWOOLE_ROOT.'/src/Resource/Http/404.html';
        // }
        // $this->response()->write(file_get_contents($file));

        $this->response()->write('404 not found');
    }

    /**
     * api接口返回数据，封装统一规则
     * @param int $code 错误代码，0为无错误
     * @param string $msg 响应提示文本
     * @param array|object $result 响应数据主体
     * @param int $count 统计数量，用于列表分页
     * @return null
     */
    public function withData(int $code = 0, string $msg = '', $result = [], int $count = -1)
    {
        $ret = [
            'status' => $code === 0?1:0,
            'code'   => $code,
            'result'   => $result,
            'message'=> $msg
        ];
        if($count >= 0) $ret['count'] = $count;

        $this->response()->withHeader('Content-type','application/json;charset=utf-8');
        $this->response()->withHeader('Access-Control-Allow-Origin','*');
        $this->write(json_encode($ret));

        //return 的目的是为了阻止方法代码向下运行
        return null;
    }

    /**
     * 表单提交验证器封装 -不推荐
     * @param null $id 表单请求提交的ID
     * @return bool|int
     */
    /*
    public function formId($id = null)
    {
        if($id == null){
            //创建formId
            $rand = rand(1000, 9999);
            $this->session('formId', $rand);
            return $rand;
        }else{
            //验证formId
            $formId = $this->session('formId');
            $this->session('formId', null);
            return $formId == $id;
        }
    }*/

    /**
     * 表单提交安全锁
     * @param string $key
     * @param int $expire 自动解锁时间，若为null则立即解锁
     * @return bool 是否正常上锁
     * @throws \EasySwoole\Redis\Exception\RedisException
     */
    protected function formLock(string $key, $expire = 1): ?bool
    {
        $redis = RedisPool::defer();
        $key = 'form_lock:'.$key;
        if($expire == null){
            $redis->del($key);
            return true;
        }
        return $redis->set($key, 1, ['NX', 'EX' => $expire]);
    }

    // function onException(\Throwable $throwable): void
    // {
    //     //直接给前端响应500并输出系统错误
    //     $this->response()->withStatus(Status::CODE_INTERNAL_SERVER_ERROR);
    //     $this->response()->write('系统繁忙,请稍后再试 ');
    // }
}