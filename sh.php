<?php
/**
 * 脚本版本(SQL)
 */
$mSQL = <<<EOL
 _   _ _              ____   ___  _     
| | | (_)   _ __ ___ / ___| / _ \| |    
| |_| | |  | '_ ` _ \\___ \| | | | |    
|  _  | |  | | | | | |___) | |_| | |___ 
|_| |_|_|  |_| |_| |_|____/ \__\_\_____|

@Author : Luck Li Di
@Email  : lucklidi@126.com
@version: v1.0.0

EOL;

$start = <<<EOL
 _   _ _                       _               _ 
| | | (_)   _ __ ___   __ _ ___| |_ ___ _ __   | |
| |_| | |  | '_ ` _ \ / _` / __| __/ _ \ '__|  | |
|  _  | |  | | | | | | (_| \__ \ ||  __/ |     |_|
|_| |_|_|  |_| |_| |_|\__,_|___/\__\___|_|     (_)

EOL;

/**
 * 初始化
 */
echo $mSQL;

/**
 * 执行授权
 */
$pwd = `pwd`;

$pwd = trim($pwd);

$soarPath = $pwd . '/luck/soar';

`chmod a+x {$soarPath}`;

/**
 * 全局软链
 */
`ln -sf {$soarPath} /usr/bin/soar`;

/**
 * 设置配置文件
 */
$configPath = $pwd . '/luck/soar.yaml';

`cp {$configPath} /etc/soar.yaml`;

/**
 * 安装成功
 * alias mysql
 * @Author: Luck Li Di
 * @Email : lucklidi@126.com
 */
$msqlPath = $pwd . '/luck/msql.php';

`echo "alias msql='php {$msqlPath}'" >>  /root/.bashrc`;

/**
 * 启动
 */
usleep(500);
echo $start . PHP_EOL;

/**
 * 全局生效
 */
`source ~/.bashrc`;
sleep(1);

/**
 * 执行帮助
 */
$help = `msql -h 2>&1`;
$help = trim($help);
if (substr($help,-5) == 'found') {
    $notice = <<<eol
 ------------------------------
|Please Run  'source ~/.bashrc'
 
|And    Run  'msql -h'
 ---------------------
 
Then, you can

use it!

eol;
    exit($notice);
}
echo $help;



