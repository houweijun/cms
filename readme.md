

## 项目介绍
financial管理后台，是基于laravel5.5+bootstrap开发，后台管理模块有系统管理模块等模块。


### 运行环境
> php7+

> mysql 5.7

> 推荐使用`wampserver 3.1.4+`

> composer 1.6.4+

> git 2.17+



## wampserver虚拟机设置

**配置文件目录路径：**

- D:\wamp64\bin\apache\apache2.4.27\conf\extra\httpd-vhosts.conf (对应安装盘符下面的位置,这里我安装位置是D盘根目录) 
	
		<VirtualHost *:80>
			ServerName financial.com
			DocumentRoot "D:/wamp64/www/financial/public"
			<Directory  "D:/wamp64/www/financial/public/">
				Options +Indexes +Includes +FollowSymLinks +MultiViews
				AllowOverride All
				Require all granted
			</Directory>
		</VirtualHost>

## host设置

**配置文件目录路径：**

- C:\Windows\System32\drivers\etc\hosts


		127.0.0.1 		financial.com 


## 项目运行设置

- 进入项目根目录，打开cmd命令行，`cp .env.example .env`

- 进入 D:\wamp64\www\financial\storage\framework文件夹下，创建sessions文件夹(注:相对项目根目录下对应的位置,这里是我对应的位置)
- 输入配置的虚拟域名，访问项目后台 `financial.com`

  ### 账号:vastlee 密码:vastlee*123 

## 公共类方法文件夹

**旧路径：**

- D:\wamp64\www\financial\app\Tools

**文件介绍：** 

|文件名|文件说明|
|:----    |:---|
|functions.php |状态公共方法  |


**新路径：**

- D:\wamp64\www\financial\bootstrap\functions.php 文件里


## 公用方法说明

|方法名|方法说明|方法所在文件|
|:----    |:---|:----- |
|getData() |过滤_token 常用到$request->all() _token 数组_token 过滤  |functions.php |
|is_verify() |验证表单数据是否为空  |functions.php |
|success() |处理成功跳转数据  |functions.php |
|error() |处理失败跳转数据  |functions.php |
|get_client_ip() |获取客户端IP地址  |functions.php |
|userBrowser() |获取浏览器信息  |functions.php |
|getTree() |权限的树状图(未用,替代方法Tree类)  |functions.php |
|zpage() |菜单分页(未用,替代方法Tree类)  |functions.php |
|restful() |Restful响应 json(未用,替代方法 Controlloer里面 方法 json_encode() )  |functions.php |
|getToken() |生成随机token  |functions.php |
|isMobile() |验证手机号  |functions.php |
|sign() |生成签名  |functions.php |
|is_sign() |生成签名  |functions.php |
|check_mobile() |检查手机号码格式  |functions.php |
|get_rand() |检查手机号码格式  |functions.php |
|get_option_value() |返回选项值  |functions.php |
|get_props_value() |返回道具名  |functions.php |
|get_array_value() |返回返回二维数组值  |functions.php |
|timediff() |计算两个时间戳之间相差的日时分秒  |functions.php |
|convertUrlQuery() |将字符串参数变为数组  |functions.php |
|getUrlQuery() |将参数变为字符串  |functions.php |
|download() |下载文件  |functions.php |
|roleShow() |后台权限 增删改 权限按钮显示函数  |functions.php |
|random_string() |随机字符串生成  |functions.php |
|str_decode() |解密用 str_encode加密的字符串  |functions.php |
|str_encode() |加密字符串  |functions.php |
|curls() |请求curl get请求  |functions.php |
|curl_get_https() |get请求curl封装  |functions.php |
|curl_post_https() |post请求curl封装 |functions.php |
|generateCode() |生成vip激活码 |functions.php |
|getSuffix() |获取文件后缀 |functions.php |
|createOrder() |创建订单号 |CreateOrder.php |
|publicUpload() |使用外网上传文件 |Oss.php |
|delImg() |使用外网删除文件 |Oss.php |

## Controller类方法说明

|方法名|方法说明|方法所在文件 Common|
|:----    |:---|:----- |
|success() |后台成功跳转数据信息  |CommonController.php |
|error() |后台错误跳转数据信息  |CommonController.php |
|redirect() |URL 重定向跳转数据信息  |CommonController.php |
|json_encode() |返回json数据  |CommonController.php |


## 视图模板说明

**视图模板文件夹：**

- D:\wamp64\www\financial\resources\views\layout 文件夹

|文件名|文件说明|所在位置|
|:----    |:---|:---|
|_base.blade.php |基础加载js css 文件  |common文件夹|
|_layout.blade.php |基础布局文件 继承layout.blade.php 文件 |common文件夹|
|_left_nav.blade.php |左边导航栏文件 |common文件夹|
|_right_top_nav.blade.php |左上导航栏文件 |common文件夹|
|_search.blade.php |搜索布局文件 |components文件夹|
|_pannel_about.blade.php |pannel标题布局文件 | components文件夹|
|_pannel_add.blade.php |pannel布局按钮文件  |components文件夹|
|_table.blade.php|table布局组件 |components文件夹|
|_iframe.blade.php|弹窗组件布局模板 |pages文件夹|
|_uploader.blade.php|弹窗组件布局模板 |pages文件夹|
|上传图片参数视图:||
|后台表单除搜索外,其他都需要ajax提交|(详见 后台 基础模块)|


## 后台js封装

**js文件位置：**

- D:\wamp64\www\financial\public\js 文件夹

|文件名|文件说明|
|:----    |:---|
|common.js |公共js文件  |
|upload.js |图片上传 时间插件方法  |
|wind.js |异步加载前端组件插件  |
|echarts.common.min.js|图表插件插件  |


## Js方法说明


|方法名|方法说明|方法所在文件|
|:----    |:---|:----- |
|button.js-ajax-submit|ajax提交表单(详见 后台 添加 编辑模块)  |common.js |
|a.js-ajax-status|ajax提交按钮修改状态  |common.js |
|uploadOneImage()|单个图片上传 前台插件 webupload  dialog 插件运用 |common.js |
|singleSort()|ajax提交单个更新排序 |common.js |
|listOrder()|ajax提交更新排序 |common.js |
|refresh()|刷新当前页面  |common.js |
|checkValid()|确认弹框   |common.js |



## Composer 第三方包说明

|扩展包名|说明|备注|
|:----    |:---|:----- |
|fideloper/proxy|laravel框架自带组件  | |
|doctrine/dbal|laravel数据库迁移修改属性依赖包  | |
|predis/predis|laravel Redis扩展包  | |
|pusher/pusher-php-server|laravel消息推送扩展包  |


## 相关文档说明

 [Composer](https://getcomposer.org/)

 [git 提交文件配置忽略规则](https://www.cnblogs.com/kevingrace/p/5690241.html)

 [Laravel社区](https://laravel-china.org/docs/laravel/5.5/)

 [easywechat](https://www.easywechat.com/docs/master/overview/)

 [Bootstrap](http://www.bootcss.com/)

 [layui](https://www.layui.com/doc/modules/layer.html)

 [Font Awesome字体](http://fontawesome.dashgame.com/)

 [wind.js](http://windapi.phpwind.co/)

 [Markdown在线编辑器](http://www.mdeditor.com/)


## 常用git命令说明

|命令|说明|备注|
|:----    |:---|:----- |
|ssh-keygen -t rsa -C "邮箱名"|生成ssh key 秘钥  | |
|git config --global user.name "yourname" |设置git全局用户名 | |
|git config --global user.email 邮箱名 |设置git全局邮箱名 | |
|git config --list |查看git配置信息 | |
|git init|初始化git仓库 | |
|git remote add origin 仓库地址|添加远程仓库地址 | |
|git add .|添加git提交文件 | |
|git commit -m "first commit"|git提交备注信息 | |
|git pull origin dev|git拉取远程分支 dev分支 | |
|git pull --allow-unrelated-histories|拉取远程仓库历史记录| 如果第一次初始化仓库，则需要拉取远程仓库历史记录|
|git diff|查看git拉取到的差异文件| 如果拉取到的文件有冲突 则需要解决文件冲突,再次重新添加git提交文件 并git提交备注信息|
|git status|查看git操作状态| |
|git push origin liujindev:dev|git本地分支推送远程分支| liujindev:本地分支 dev:远程dev分支 |
|git push -u origin dev|git 当前本地分支 强制 覆盖远程dev分支| `*注:一般不推荐使用,了解下`|
|git push -f origin dev|git 当前本地分支 强制 覆盖远程dev分支| `*注:一般不推荐使用,了解下`|
|git push --set-upstream origin dev|git 当前本地分支 强制 覆盖远程dev分支| `*注:一般不推荐使用,了解下`|
|git branch|查看git已有分支| |
|git checkout -b 新分支名|切换git分支| |
|git stash|清空git 本地修改文件缓存| `*注:一般用于本地修改文件作废,强制拉取远程分支内容,(git pull origin dev)` |
|git log|查看git 提交的版本号 信息| |
|git reset --hard '版本号'|git 回退到指定的版本|  `*注:用git log 查看版本号`|
|git reset --hard HEAD~|git 回退到上一次提交版本|  `*注:~ 后面的数字表示回退几次提交，默认是一次`|
|git merge|合并分支|
|git rm -r --cached .|删除暂存缓存|

## vastlee管理后台密码

|后台账号|密码|
|:----    |:---|
|vastlee|vastlee*123 |



## 网站运行步骤

```shell
#1.复制.env.example文件 并修改为 .env 并配置数据库
#2.创建数据库
#3.生成修改key
php artisan key:generate  
#4.进入网站根目录，创建日志文件夹 logs
mkdir -p storage/logs
#5.运行数据库迁移
     #系统模块
php artisan migrate:refresh --seed --path=database/migrations/admin/system
     #业务模块表
php artisan migrate --path=database/migrations/admin/module
#6.配置虚拟主机域名
#7.填充模块菜单数据
php artisan db:seed --class=UpdateSeeder
#8.域名 访问后台
```