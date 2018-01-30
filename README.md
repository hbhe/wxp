# 微信公众号运营平台

1、下载源码
* git clone https://github.com/hbhe/wxp
* composer install (如果很慢，可以直接copy vendor目录)

2、建database
* CREATE DATABASE wxp DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_unicode_ci;

3、创建.env文件(可由.env.list复制后编辑)，编辑配置文件, 设置db参数


```php
DB_DSN           = mysql:host=localhost;port=3306;dbname=wxp

YII_DEBUG   = true
YII_ENV     = dev
DB_USERNAME      = root
DB_PASSWORD      = 
FRONTEND_URL    = http://127.0.0.1/wxp/frontend/web/
BACKEND_URL     = http://127.0.0.1/wxp/backend/web/
STORAGE_URL     = http://127.0.0.1/wxp/storage/web
MOBILE_URL     =  http://127.0.0.1/wxp/mobile/web

#YII_DEBUG   = false
#YII_ENV     = prod
#DB_USERNAME      = root
#DB_PASSWORD = xxx
#FRONTEND_URL    = http://www.mysite.com/
#BACKEND_URL     = http://admin.mysite.com/
#STORAGE_URL     = http://storage.mysite.com/
#MOBILE_URL     = http://m.mysite.com/

#m.mysite.com        M端
#admin.mysite.com    后台
#static.mysite.com   静态
#storage.mysite.com  上传
#wechat.mysite.com   微信服务器
#test.mysite.com     测试

# Other
# -----
#SMTP_HOST = localhost
#SMTP_PORT = 25

SMTP_HOST = smtp.qq.com
SMTP_PORT = 465

FRONTEND_COOKIE_VALIDATION_KEY = jZxpHa-6KwAO4nN4hnliFJqmU4gptNQr
BACKEND_COOKIE_VALIDATION_KEY = pdSO0iO-R7MIuJ92tn_Xb4q-AVHrcsuY

ADMIN_EMAIL    = webmaster@mysite.com

ROBOT_EMAIL    = robot@mysite.com

GITHUB_CLIENT_ID = your-client-id
GITHUB_CLIENT_SECRET = your-client-secret

FACEBOOK_CLIENT_ID = your-client-id
FACEBOOK_CLIENT_SECRET = your-client-secret

GLIDE_SIGN_KEY = pXvFIG5UXyvLh1_cAuSdR41gJjFzOTan
GLIDE_MAX_IMAGE_SIZE = 4000000
```


4、执行命令，安装相关的数据库表

cd console               

执行以下步骤:
* php yii migrate/up --migrationPath=@common/wosotech/modules/keyStorage/migrations
* php yii migrate --migrationPath=@noam148/imagemanager/migrations
* php yii migrate/up
* php yii rbac-migrate   // 给初始化用户分配权限


以下为可选:
* php yii migrate/up --migrationPath=@common/modules/bargain/migrations      // 砍价活动
* php yii migrate/up --migrationPath=@common/modules/wall/migrations         // 微信现场活动(包括：微信签到，微信消息互动上墙， 微信摇一摇抽奖)
* php yii migrate/up --migrationPath=@common/modules/redpack/migrations      // 会员推荐送红包活动

将相关目录设为可写:
```
cd ..
chmod -R 777 ./backend/web
chmod -R 777 ./backend/runtime
chmod -R 777 ./common/runtime
```

5、到微信公众号官网进行配置, url(如http://wechat.mysite.com/index.php?r=site&gh_id=gh_6b9b67032eaa )

6、WXP后台访问地址: http://127.0.0.1/wxp/backend/web/    用户名: webmaster   密码: webmaster
   
7、登录WXP后台后，接入公众号, 输入appid, appsecret, token, ... 
（这种输入方式比较繁琐，也可以以扫码授权的形式接入公众号，不过你先得到微信第三方开放平台申请成为服务商，然后还要在wxp中配置第三方开放平台参数）


#本平台特点:

* 支持管理多个公众号，支持扫码授权（不需要输入繁琐的appid, secret等）
* 可以对第三方开发者提供获取openid的接口(网页授权); 不仅可以自定义关键词对消息进行处理，还可以将收到的微信消息原封不动地转发给第三方, 接受第三方处理消息后的返回结果
* 以模块的形式上架营销活动，并提供了一个砍价活动例子(前后端分离，RESTful接口)
* 粉丝管理、菜单管理、会员管理、部门管理、关键词定义、
* 3个微信现场活动: 微信签到上墙、微信互动消息上墙、微信摇一摇抽奖


#开发微信公众号平台的要点：

要通过网页授权获取openid，必须先知道当前页面是使用的哪个公众号，有2种方式:
* 在每个页面的url中都加上一个gh_id参数，如http://mysite.com/index.php?gh_id=gh_123456, PHP通过$_GET['gh_id']得知gh_id. 这种方式不太可靠
* 通过域名，http://wx123456.mysite.com， ，配置一下nginx(请参考sample.nginx.conf文件), 将wx123456传到PHP环境中, PHP通过$_SERVER['gh_sid']可取到值wx123456, 一般就以appid作为域名前缀, 这种方式好

知道是哪个公众号后，每个PHP页面就都可以通过 $openid = \common\wosotech\Util::getSessionOpenid(); 自动获取当前用户的openid


**如果觉得此项目对你有用，请点亮star；如果在使用中遇到问题请Q：57620133**

