# 微信公众号运营平台

1、下载源码
git clone https://github.com/hbhe/wxp
composer install (如果很慢，可以直接copy vendor目录)

2、建database
CREATE DATABASE wxp DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_unicode_ci;

3、复制.env.list文件 改为.env后缀，并配置文件, 设置db参数
Edit the file .env

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
cd console                                                           // php yii app/setup ?
必选安装:
php yii migrate/up --migrationPath=@common/wosotech/modules/keyStorage/migrations
php yii migrate --migrationPath=@noam148/imagemanager/migrations
php yii migrate/up
php yii rbac-migrate   // 给初始化用户分配权限

可选安装:
php yii migrate/up --migrationPath=@common/modules/bargain/migrations      // 砍价活动
php yii migrate/up --migrationPath=@common/modules/wall/migrations         // 微信现场活动(包括：微信签到，微信消息互动上墙， 微信摇一摇抽奖)
php yii migrate/up --migrationPath=@common/modules/redpack/migrations      // 会员推荐送红包活动

5、
后台访问地址: http://127.0.0.1/wxp/backend/web/

用户名: webmaster     密码: webmaster


本平台特点:

1、支持管理多个公众号
2、可以对第三方开发者提供获取openid的接口(网页授权); 不仅可以自定义关键词对消息进行处理，还可以将收到的微信消息原封不动地转发给第三方, 接受第三方处理消息后的返回结果
3、以模块的形式支付上架营销活动，并提供了一个砍价活动例子(RESTful)
4、粉丝管理、菜单管理、会员管理、部门管理、关键词定义、
5、3个微信现场活动: 微信签到上墙、微信互动消息上墙、微信摇一摇抽奖


