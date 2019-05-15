<?php
/**
 * 脚本标识
 */
$m_SQL = <<<EOL
 _   _ _              ____   ___  _     
| | | (_)   _ __ ___ / ___| / _ \| |    
| |_| | |  | '_ ` _ \\___ \| | | | |    
|  _  | |  | | | | | |___) | |_| | |___ 
|_| |_|_|  |_| |_| |_|____/ \__\_\_____|

EOL;

/**
 * 脚本版本
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

/**
 * 数据库设置
 */
$database = 'LuckLi';

/**
 * 设置DNS版本路径
 */
$_dir   = '/tmp/msql';

/**
 * 日志输出(可配置)
 */
$outLog = '/dev/null';

/**
 * yaml配置参数
 */
$yamFile = '/etc/soar.yaml';

if (!is_dir($_dir)) {
    mkdir($_dir,0755,true);
}
$param_arr   = $argv;

//---------------------------------------------------------------------------------------------------------------------
/**
 * 初始化（输出配置信息）
 * @since Good, it start, Enjoy!
 */
//---------------------------------------------------------------------------------------------------------------------
$version     = explode(PHP_EOL,file_get_contents($yamFile));
$new_version = array_flip($version);
$start_key   = $new_version['test-dsn:'];
$addR        = explode(':',$version[$start_key+1])[1];
$dbName      = explode(':',$version[$start_key+2])[1];
$user        = explode(':',$version[$start_key+3])[1];
$pwd         = explode(':',$version[$start_key+4])[1];

//---------------------------------------------------------------------------------------------------------------------
/**
 * 版本信息：msql -e
 */
//---------------------------------------------------------------------------------------------------------------------
if (isset(array_flip($param_arr)['-e'])){
    echo $mSQL;
    echo <<<EOL
---------------------------------------------------------------------------------------------------------------------------------------------------------
查询自动标识符	| 类型	     | 表	  | 分区	      | jion类型           | 可能索引	      | 索引 | 索引长度	               |字段或常量| 扫面行数 | 过滤百分比 |额外信息 |
---------------------------------------------------------------------------------------------------------------------------------------------------------
id	        | Select_type| table  | partitions| type              | possible_keys| Key	| Ken_len                  |Ref	    | Rows   | Filtered |Extra   |
---------------------------------------------------------------------------------------------------------------------------------------------------------
1，2，null	| SIMPLE	 |表或衍生  |	null	  |ALL< index 		  |              |      |keyLen(key)、varchar(n)    | const  |扫描读取 |	         |       |
            | UNION      |        |           |< range~index_merge|              |      |如果是utf8编码,则是3n+2字节;  | null   |数据行数 |           |        |
	        | SUBQUERY   |        |           |< ref< eq_ref      |              |      |如果是utf8mb4编码,则是4n+2字节|        |        |           |       |
		    |			 |		  |           |< const< system    |              |      |字段属性:NULL属性占用一个字节  |        |        |           |       |
----------------------------------------------------------------------------------------------------------------------------------------------------------
.

EOL;
    die;
}

//---------------------------------------------------------------------------------------------------------------------
/**
 * 版本信息：msql -v
 */
//---------------------------------------------------------------------------------------------------------------------
if (isset(array_flip($param_arr)['-v'])){
    echo $mSQL;
echo <<<EOL
数据库地址 ： {$addR}

数据库名称 ： {$dbName}

数据库账号 ： {$user}

数据库密码 ： {$pwd}
.

EOL;
die;
}

//---------------------------------------------------------------------------------------------------------------------
/**
 * 帮助信息：msql -h
 */
//---------------------------------------------------------------------------------------------------------------------
if (isset(array_flip($param_arr)['-h'])) {
    echo $m_SQL;
    echo <<<EOL
参数说明：

-q : SQL   语句 （例如: select * from user）

-d : DNS   配置 （例如: 只修改数据库"@/database" eg: msql -d "@/LuckLi"; 修改数据库及Host"host/database" eg: msql -d "127.0.0.1/LuckLi"）

-v : DBA   参数信息

-h : help something

-e : show explain 解析
.

EOL;
    die;
}

//---------------------------------------------------------------------------------------------------------------------
/**
 * 设置Database
 * msql -d  ""@/database""
 */
//---------------------------------------------------------------------------------------------------------------------
if (isset(array_flip($param_arr)['-d'])) {
    $d_key   = array_flip($param_arr)['-d'];
    $d_value = $param_arr[$d_key+1];
    if (!strpos($d_value,'/')) {
        echo "DNS设置错误，请认真核对后重试，只修改数据库\"@/database\" eg: msql -d \"@/LuckLi\"; 修改数据库及Host\"host/database\" eg: msql -d \"127.0.0.1/LuckLi\"" . PHP_EOL;
        echo <<<EOL
查看版本信息，请输入 -v

查看帮助信息，请输入 -h
.

EOL;
    die;
    }
    /**
     * 执行dns设置
     */
    try {
        $database = explode('/',$d_value)[1];
        $db_host  = explode('/',$d_value)[0];
        if ($db_host == '@') {
            $db_host = $addR;
        }
        $myfile   = fopen($yamFile, "w") or die("Unable to open file! The file path is {$yamFile}" . PHP_EOL);
        $yamL = <<<eol
# 线上环境配置
online-dsn:
  addr: {$db_host}:3306
  schema: {$database}
  user: root
  password: ''
  disable: false
# 测试环境配置
test-dsn:
  addr: {$db_host}:3306
  schema: {$database}
  user: root
  password: ''
  disable: false
# 是否允许测试环境与线上环境配置相同
allow-online-as-test: true
# 是否清理测试时产生的临时文件
drop-test-temporary: true
# 语法检查小工具
only-syntax-check: false
sampling-statistic-target: 100
sampling: false
# 日志级别，[0:Emergency, 1:Alert, 2:Critical, 3:Error, 4:Warning, 5:Notice, 6:Informational, 7:Debug]
log-level: 7
log-output: /dev/null
# 优化建议输出格式
report-type: markdown
ignore-rules:
- ""
# 黑名单中的 SQL 将不会给评审意见。一行一条 SQL，可以是正则也可以是指纹，填写指纹时注意问号需要加反斜线转义。
blacklist: {$_dir}/soar.blacklist
# 启发式算法相关配置
max-join-table-count: 5
max-group-by-cols-count: 5
max-distinct-count: 5
max-index-cols-count: 5
max-total-rows: 9999999
spaghetti-query-length: 2048
allow-drop-index: false
# EXPLAIN相关配置
explain-sql-report-type: pretty
explain-type: extended
explain-format: traditional
explain-warn-select-type:
- ""
explain-warn-access-type:
- ALL
explain-max-keys: 3
explain-min-keys: 0
explain-max-rows: 10000
explain-warn-extra:
- ""
explain-max-filtered: 100
explain-warn-scalability:
- O(n)
query: ""
list-heuristic-rules: false
list-test-sqls: false
verbose: true
eol;
        fwrite($myfile, $yamL);
        fclose($myfile);
        echo $m_SQL.PHP_EOL;
        echo "数据库配置完成: Database Config is Completed Successfully" . PHP_EOL;
        echo ".";
    } catch (Exception $e) {
        echo 'error=' . $e->getMessage() . PHP_EOL;
    }
    die;
}

//---------------------------------------------------------------------------------------------------------------------
/**
 * 执行语句
 * 帮助提示
 */
//---------------------------------------------------------------------------------------------------------------------
if (!isset(array_flip($param_arr)['-q'])) {
    echo <<<EOL
执行sql优化，请输入 -q

查看版本信息，请输入 -v

查看帮助信息，请输入 -h
.

EOL;
die;
}

//---------------------------------------------------------------------------------------------------------------------
/**
 * 执行sql优化
 * msql -q "select * from LuckLi"
 */
//---------------------------------------------------------------------------------------------------------------------
if (isset(array_flip($param_arr)['-q'])) {

    $q_key   = array_flip($param_arr)['-q'];
    $q_value = $param_arr[$q_key+1];
    if (!strpos($q_value,'from')) {
        echo "sql语句错误，请认真核对后重试，eg: msql -q \"select * from LuckLi\"" . PHP_EOL;
        echo <<<EOL
查看版本信息，请输入 -v

查看帮助信息，请输入 -h
.

EOL;
    die;
    }
    /**
     * 执行sql优化
     */
    $cmd = "echo \"{$q_value}\" | soar";
    try {
        $res = shell_exec($cmd . " 2>&1");
        echo $res . PHP_EOL;
        print_r("---the end---\n");
    } catch (Exception $e) {
        echo 'error=' . $e->getMessage() . PHP_EOL;
    }
}
