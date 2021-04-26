<?php
/**
 * 模板视图配置
 */
return [
    'enable'      =>    false, //启用视图模板
    'engine'      =>    'think', //视图引擎
    'view_path'   =>	EASYSWOOLE_ROOT.'/Public/', //视图模板存放路径
    'cache_path'  =>	EASYSWOOLE_ROOT.'/Temp/runtime/', //缓存路径
    'view_suffix' =>    'htm' //模板文件后缀名
];
