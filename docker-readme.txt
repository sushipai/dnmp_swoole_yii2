# 在此之前先要了解 docker 一些基本用法https://juejin.im/post/5cacbfd7e51d456e8833390c

# 首先确保你安装了 docker 和 docker-compose 和 git 和 phpcomposer

# download git 
https://www.git-scm.com/download/win 

# download docker
https://www.docker.com/products/docker-desktop

## windows安装php composer
https://getcomposer.org/Composer-Setup.exe

## 非windows安装php composer
wget https://getcomposer.org/download/1.8.5/composer.phar
mv composer.phar /usr/local/bin/composer

# 环境配置 
## nginx:1.15.12
## mysql:5.7.25
## php:7.2.17
## php redis:5.0.4
## php swoole:4.3.3

# 安装后的工作目录  
├ www   					工作目录
├── yii2					yii2项目   
├ swoole
├── conf                    配置文件目录
│   ├── conf.d              Nginx用户站点配置目录
│   ├── nginx.conf          Nginx默认配置文件
│   ├── mysql.cnf           MySQL用户配置文件
│   ├── php-fpm.conf        PHP-FPM配置文件（部分会覆盖php.ini配置）
│   └── php.ini             PHP默认配置文件
├── Dockerfile              PHP镜像构建文件
├── log                     Nginx日志目录
├── mysql                   MySQL数据目录
└── source.list             Debian源文件

#######################################################
## 配置composer使用国内镜像
composer config -g repo.packagist composer https://packagist.phpcomposer.com

#随便新建一个工作文件夹如project

mkdir project

cd project

## 获取源代码
git clone https://github.com/sushipai/dump_swoole_yii2.git

cd dump_swoole_yii2/swoole
## 运行镜像环境配置compose以守护进程模式运行加
docker-compose up -d

docker-compose up -d --build

#######################################################
## 本地安装了git php  php composer 创建新项目例子
cd ../www/yii2

composer update

php init # 选择开发版 按0 再按yes

#######################################################
## 本地未安装相关软件可以在服务端 创建新项目例子

## docker exec命令 	#### 在window cmd 有效 MINGW64 无效 
# 进入swoole_php72_1

docker exec -it  swoole_php72_1 /bin/bash

composer update

php init # 选择开发版 按0 再按yes

#######################################################
# 客户端执行服务器swoole命令 #### 在window cmd 有效 MINGW64 无效 
docker exec -d -w /var/www/html/项目目录 swoole_php72_1 php 项目文件名称 start

## 配置数据库

使用客户端工具navicat 

数据库配置 
host:localhost
port:3306 
username:root
password:root

#######################################################
Swoole IDE Helper
composer require --dev "eaglewu/swoole-ide-helper:dev-master"

#######################################################
windows 配置域名指向
C:\Windows\System32\drivers\etc\hosts 配置域名指向
127.0.0.1 jjcms.com #测试https 证书是正规申请的不会阻拦

# 访问网站
http://127.0.0.1/check #测试yii2 mysql redis 状态

#前端
http://127.0.0.1/ 
#后端
http://127.0.0.1/backend/ 
#api
http://127.0.0.1/api/ 

# 正规https访问jjcms.com替换127.0.0.1
http://jjcms.com
https://jjcms.com
#######################################################

## compose以守护进程模式运行加-d选项
docker-compose up -d

docker-compose up  会优先使用已有的容器，而不是重新创建容器。

docker-compose up -d --force-recreate 使用 --force-recreate 可以强制重建容器 （否则只能在容器配置有更改时才会重建容器）

docker-compose down 停止所有容器，并删除容器 （这样下次使用docker-compose up时就一定会是新容器了）

#######################################################

## 查看容器
docker ps -a

## 查看镜像
docker images

## 批量停止 && 批量删除 && 删除镜像
docker stop $(docker ps -a -q) && docker rm $(docker ps -a -q) && docker rmi $(docker images) && docker images && docker ps -a

## 查看容器和镜像
docker images && docker ps -a

## 重启ningx
docker exec swoole_nginx_1 nginx -s reload

## 启动镜像
docker start swoole_redis_1 swoole_mysql_1 swoole_php72_1 swoole_nginx_1

## 启动镜像
docker-compose start

在容器 swoole_php72_1 中开启一个交互模式的终端:  MINGW64 无效
docker exec -it  swoole_php72_1 /bin/bash