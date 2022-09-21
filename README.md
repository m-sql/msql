#  mSQL (my mysql score)

## 是一个对SQL进行优化和改写的自动化工具。

# 功能特点
* 跨平台支持（支持Linux, Mac环境，Windows环境理论上也支持，不过未全面测试）
* 目前只支持 MySQL 语法族协议的SQL优化
* 支持基于启发式算法的语句优化
* 支持复杂查询的多列索引优化（UPDATE, INSERT, DELETE, SELECT）
* 支持EXPLAIN信息丰富解读
* 支持SQL指纹、压缩和美化
* 支持同一张表多条ALTER请求合并
* 支持自定义规则的SQL改写

#  安装使用
## 依赖软件
##### 一般依赖
* Go 1.10+
* git

### 0、下载源码
```linux
 [root@lidi home]# git clone https://github.com/m-sql/msql.git
  Cloning into 'msql'...
  remote: Enumerating objects: 26, done.
  remote: Counting objects: 100% (26/26), done.
  remote: Compressing objects: 100% (21/21), done.
```
### 1、linux下执行php
``` linux
cd mysql

[root@test1 msql]# php sh.php 
 _   _ _              ____   ___  _     
| | | (_)   _ __ ___ / ___| / _ \| |    
| |_| | |  | '_ ` _ \___ \| | | | |    
|  _  | |  | | | | | |___) | |_| | |___ 
|_| |_|_|  |_| |_| |_|____/ \__\_\_____|

@Author : Luck Li Di
@Email  : lucklidi@126.com
@version: v1.0.0
 _   _ _                       _               _ 
| | | (_)   _ __ ___   __ _ ___| |_ ___ _ __   | |
| |_| | |  | '_ ` _ \ / _` / __| __/ _ \ '__|  | |
|  _  | |  | | | | | | (_| \__ \ ||  __/ |     |_|
|_| |_|_|  |_| |_| |_|\__,_|___/\__\___|_|     (_)

 ------------------------------
|Please Run  'source ~/.bashrc'
 
|And    Run  'msql -h'
 ---------------------
 
Then, you can

use it!

```
### 2、立刻即用
##### I::配置DNS（数据库）
```
[root@test1 msql]# msql -h
 _   _ _              ____   ___  _     
| | | (_)   _ __ ___ / ___| / _ \| |    
| |_| | |  | '_ ` _ \___ \| | | | |    
|  _  | |  | | | | | |___) | |_| | |___ 
|_| |_|_|  |_| |_| |_|____/ \__\_\_____|
参数说明：
-q : sql 语句 （例如: select * from user）
-d : dns 配置 （例如: "@/database" eg: msql -d "@/LuckLi" 修改数据库及Host"host/database" eg: msql -d "127.0.0.1/LuckLi"）
-h : help something
-e : show explain 解析
```
##### II::检测数据库
```
[root@test1 msql]# msql -v
 _   _ _              ____   ___  _     
| | | (_)   _ __ ___ / ___| / _ \| |    
| |_| | |  | '_ ` _ \___ \| | | | |    
|  _  | |  | | | | | |___) | |_| | |___ 
|_| |_|_|  |_| |_| |_|____/ \__\_\_____|

@Author : Luck Li Di
@Email  : lucklidi@126.com
@version: v1.0.0

数据库地址 ：  127.0.0.1
数据库名称 ：  msql
数据库账号 ：  root
数据库密码 ：  ''
```
### III::执行SQl查询
```
[root@test1 msql]# msql -q "select * from luck"
# Query: 64B551FD4BE6BD74

★ ★ ★ ☆ ☆ 75分

 sql

SELECT  
  * 
FROM  
  luck
  

##最外层SELECT未指定WHERE条件

* **Item:**  CLA.001

* **Severity:**  L4

* **Content:**  SELECT语句没有WHERE子句，可能检查比预期更多的行(全表扫描)。对于SELECT COUNT(\*)类型的请求如果不要求精度，建议使用SHOW TABLE STATUS或EXPLAIN替代。

##  不建议使用SELECT * 类型查询

* **Item:**  COL.001

* **Severity:**  L1

* **Content:**  当表结构变更时，使用\*通配符选择所有列将导致查询的含义和行为会发生更改，可能导致查询返回更多的数据。

---the end---

```

## 协议

[![996.icu](https://img.shields.io/badge/link-996.icu-red.svg)](https://996.icu)
[![LICENSE](https://img.shields.io/badge/license-Anti%20996-blue.svg)](https://github.com/996icu/996.ICU/blob/master/LICENSE)

[MIT : license](https://github.com/m-sql/msql/blob/master/LICENSE)
