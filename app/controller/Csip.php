<?php
namespace app\controller;

use sword\Redis;
use sword\orm\Db;
use app\packer\CsipPacker;

class Csip
{
    private $server;
    private $fd;
    private $csip;

    //入口函数
    public function handle($server, $fd, $csip)
    {
        //功能码与方法绑定
        $action = [
            'C01A' => 'login',
            'C03B' => 'broadcast',
            'C04G' => 'getGroup'
        ];

        $this->server = $server;
        $this->fd = $fd;
        $this->csip = $csip;

        if(isset($action[$csip->funcCode])){
            //取出方法名
            $action_name = $action[$csip->funcCode];
            $this->$action_name($server,$fd,$csip);
        }else{
            echo "未定义的功能码:".$csip->funcCode."\n".$csip->encode();
        }

    }

    //登录用户
    public function login()
    {
        $ret = new CsipPacker();
        
        //定义为广播事件
        $ret->bodyParam['broadcast'] = 1;

        //设置事件名称 -登录返回结果
        $ret->bodyParam['event'] = 'loginCallBack';

        //查询用户
        $check = Db::queryOne("SELECT uid,realname,phone,avatar,status FROM `jz_user` WHERE  `phone` = ? and `password` = ? LIMIT 1",
            [$this->csip->bodyParam['uname'],md5($this->csip->bodyParam['pwd'])]);

        if(!$check){
            $ret->bodyData = djson(1, '账号密码错误');
            $this->server->send($this->fd, $ret->encode());
        }else{
            session(config('app.userFd').$check['uid'],$this->fd);

            $rets = Db::execUp("UPDATE jz_im_group_user SET fd=? WHERE uid=?", [$this->fd,$check['uid']]);

            $ret->bodyData = djson(0, '登陆成功'.$rets, $check);
            $this->server->send($this->fd, $ret->encode());

            $this->getGroup();
            // $ret->bodyData = djson(0, session($this->fd));
    
        }

            echo "发送数据：".$ret->encode()."\n";

    }

    //获取分组
    public function getGroup()
    {
        $ret = new CsipPacker();
        
        //定义为广播事件
        $ret->bodyParam['broadcast'] = 1;

        //设置事件名称 -登录返回结果
        $ret->bodyParam['event'] = 'groupRefresh';

        if(isset($this->csip->bodyParam['uid'])){
            $check = Db::query("SELECT any_value(g.id) as id,any_value(g.name) as name FROM jz_im_group as g join jz_im_group_user as gu on gu.imId=g.id WHERE gu.uid = ? group by gu.imId",[$this->csip->bodyParam['uid']]);
            $this->server->send($this->fd, "ok\r\n");
            $this->fd = session(config('app.userFd').$this->csip->bodyParam['uid']);
        }else{
            $check = Db::query("SELECT any_value(g.id) as id,any_value(g.name) as name FROM jz_im_group as g join jz_im_group_user as gu on gu.imId=g.id WHERE gu.fd = ? group by gu.imId",[$this->fd]);
        }
        if($check == NULL){
            $check = [];
        }
        $ret->bodyData = djson(0, 'ok', $check);
        $this->server->send($this->fd, $ret->encode());
    }

    //广播语音
    public function broadcast()
    {
        $conn_list = Db::name('im_group_user')
        ->where(['uid'=>['<>', $this->csip->bodyParam['uid']], 'imId'=>$this->csip->bodyParam['group_id']])
        ->select();

        //功能码
        $this->csip->funcCode = 'S03B';

        $data = $this->csip->encode();
        //遍历数组发送数据
        foreach ($conn_list as $k) {
            if($this->server->exist($k['fd'])){
                $this->server->send($k['fd'], $data);
            }
        }

    }

    //使用实例
    public function db()
    {
        $data = Db::name('user')
        ->field('uid,phone')
        ->where(['uid','<',10])
        ->select();
        print_r($data);

        Redis::set('user','private');
        echo Redis::get('user');
    }

}