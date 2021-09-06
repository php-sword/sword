#!/usr/bin/env php
<?php
/**
 * 因为nginx配置代理、ssl、websocket较为复杂 所以编写了这个工具。
 * 用法： 1.在$config变量中配置你的信息
 *       2.在该目录运行命令 ./nginx_make.php
 *       3.在该目录会自动生成 nginx.conf,这个文件就是配置文件，将它添加到nginx中
 *       4.重启nginx服务器即可
 * @authar kyour.cn
 */
$config = [
    //类型 1:http 2:https 3:http+https
    'type' => 3,

    //内网服务端口 与dev中配置的端口一致
    'server_port' => 8100,
    //http映射端口 -外网
    'http_port' => 80,
    //https映射端口 -外网
    'https_port' => 443,

    //外网访问域名 多个用空格分割
    'host_name' => 'sword.kyour.cn',

    //应用根目录 绝对路径，以/结尾
    'root_path' => __DIR__ . '/', //默认当前路径
    //静态资源目录名称
    'public_path' => 'Public',

    //静态首页 -开启后直接由nginx响应 /index
    'static_index' => true,

    //websocket的url,为空则不开启,不支持填写'/'
    'ws_url' => '/ws',

    //https的ssl证书文件（绝对路径） -仅开启https有效
    'ssl_cer' => '/usr/local/nginx/conf/ssl/sword.kyour.cn/fullchain.cer',
    //https的ssl密钥文件（绝对路径） -仅开启https有效
    'ssl_key' => '/usr/local/nginx/conf/ssl/sword.kyour.cn/sword.kyour.cn.key',

    //图片缓存时间
    'img_cache' => '3d',
    //资源文件缓存时间 （js、css、字体）
    'res_cache' => '7d'
];

// ===============================================================================================
//           非专业人员勿动以下内容
// ===============================================================================================

$str_local = '
    location / {
        proxy_http_version 1.1;
        proxy_set_header Connection "keep-alive";
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;

        '.
    ($config['static_index']?'#接管静态主页
        if ($uri = "/"){
            rewrite ^(.*)$ /index last;
        }':'')
    .'
        #html文件存在 - 重写路径 .html
        if (-f "${request_filename}.html") {
            rewrite ^(.*)$ /$1.html break;
        }
        #代理swoole -没有静态文件的情况下
        if (!-f $request_filename){
			proxy_pass http://127.0.0.1:8108;
        }
    }
';

$str_http = '
server
{
    listen '.$config['http_port'].';
    #listen [::]:80;
    server_name '.$config['host_name'].';
    root '.$config['root_path'].$config['public_path'].';

    location ~ .*\.(gif|jpg|png|bmp|ico)$
    {
        expires      '.$config['img_cache'].';
    }

    location ~ .*\.(js|css|ttf)?$
    {
        expires      '.$config['res_cache'].';
    }

    location ~ .*\.(html|htm)$ {
        expires      30s;
        #禁止缓存，每次都从服务器请求
        #add_header Cache-Control no-store;
    }
    '.
    //判断是否存在ws_url
    ($config['ws_url']?'
    # Websocket支持
    location '.$config['ws_url'].' {
        proxy_http_version 1.1;
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;

        proxy_set_header Upgrade $http_upgrade;   # 升级协议头
        proxy_set_header Connection upgrade;
        proxy_pass http://127.0.0.1:'.$config['server_port'].';
    }
    ':'').
    $str_local.
    '    access_log off;
}
';

$str_https = '
# Https配置，其他配置与上面相同，只是多了ssl证书配置
server
{
    listen '.$config['https_port'].' ssl http2;
    #listen [::]:443 ssl http2;
    server_name '.$config['host_name'].';
    root '.$config['root_path'].$config['public_path'].';

    ssl_certificate '.$config['ssl_cer'].';
    ssl_certificate_key '.$config['ssl_key'].';
    ssl_session_timeout 5m;
    ssl_protocols TLSv1 TLSv1.1 TLSv1.2 TLSv1.3;
    ssl_prefer_server_ciphers on;
    ssl_ciphers "TLS13-AES-256-GCM-SHA384:TLS13-CHACHA20-POLY1305-SHA256:TLS13-AES-128-GCM-SHA256:TLS13-AES-128-CCM-8-SHA256:TLS13-AES-128-CCM-SHA256:EECDH+CHACHA20:EECDH+CHACHA20-draft:EECDH+AES128:RSA+AES128:EECDH+AES256:RSA+AES256:EECDH+3DES:RSA+3DES:!MD5";
    ssl_session_cache builtin:1000 shared:SSL:10m;

    location ~ .*\.(gif|jpg|png|bmp|ico)$
    {
        expires      '.$config['img_cache'].';
    }

    location ~ .*\.(js|css|ttf)?$
    {
        expires      '.$config['res_cache'].';
    }

    location ~ .*\.(html|htm)$ {
        expires      30s;
        #禁止缓存，每次都从服务器请求
        #add_header Cache-Control no-store;
    }
    '.
    //判断是否存在ws_url
    ($config['ws_url']?'
    # Websocket支持
    location '.$config['ws_url'].' {
        proxy_http_version 1.1;
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;

        proxy_set_header Upgrade $http_upgrade;   # 升级协议头
        proxy_set_header Connection upgrade;
        proxy_pass http://127.0.0.1:'.$config['server_port'].';
    }
    ':'').
    $str_local.
    '    access_log off;
}
';

$conf_val = '';
if($config['type'] == 1){
    $conf_val = $str_http;
}elseif($config['type'] == 2){
    $conf_val = $str_https;
}else{
    $conf_val = $str_http . $str_https;
}
file_put_contents("./nginx.conf",$conf_val);
$path = __DIR__;
echo "Output successful.\nPath: {$path}/\nFile: nginx.conf\n";
