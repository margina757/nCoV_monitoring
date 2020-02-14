
## 疫情出入管理

苟利国家生死以，岂因祸福避趋之，很惭愧，就做了一点微小的工作

#### 用户端：
    扫码打开页面，不同地区（小区|酒店|村）对应不同的二维码，填写出入信息。
#### 上峰管控端：
    后台打开疫情出入统计后台，查看各个地区的出入登记情况，同时可以添加修改地区（小区|酒店|村）
    如果发生疫情，可在后台进行搜索排查用户出入信息，为疫情管控提供及时可靠的帮助
    
#### 部署:
     获取代码: git clone https://github.com/margina757/nCoV_monitoring  
     1.
        composer 安装依赖
     2.
        项目根目录建立.env 文件
        参考env_example 配置数据库地址
     3.
        php init 初始化 选择生产环境
     4.
        php yii migrate 生成数据表
     5.
        使用nginx 或者 apache 配置域名 以及指向对应入口文件
     可以前后台分为两个二级域名处理
     示例：
        前台
            xxx.xxx.com
            对应目录指向  frontend/web 
            默认入口index index.html
        后台
            xxx-admin.xxx.com
            对应目录指向  backend/web
            默认入口 index index.php
        apache 配置    
            <VirtualHost *:80>
                ServerName rbac.yii.local.com
                DocumentRoot /home/www/yii/rbac/web
                <Directory "/home/www/yii/rbac/web">
                    Require all granted
                    Allow from all
                    RewriteEngine on
                    RewriteCond %{REQUEST_FILENAME} !-f
                    RewriteCond %{REQUEST_FILENAME} !-d
                    RewriteRule . index.php
                </Directory>
            </VirtualHost>
        nginx 配置
            server {
                charset utf-8;
                client_max_body_size 128M;
            
                listen 80; ## listen for ipv4
                #listen [::]:80 default_server ipv6only=on; ## listen for ipv6
            
                server_name mysite.test;
                root        /path/to/basic/web;
                index       index.php;
            
                access_log  /path/to/basic/log/access.log;
                error_log   /path/to/basic/log/error.log;
            
                location / {
                    # Redirect everything that isn't a real file to index.php
                    try_files $uri $uri/ /index.php$is_args$args;
                }
            
                # uncomment to avoid processing of calls to non-existing static files by Yii
                #location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
                #    try_files $uri =404;
                #}
                #error_page 404 /404.html;
            
                # deny accessing php files for the /assets directory
                location ~ ^/assets/.*\.php$ {
                    deny all;
                }
                
                location ~ \.php$ {
                    include fastcgi_params;
                    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                    fastcgi_pass 127.0.0.1:9000;
                    #fastcgi_pass unix:/var/run/php5-fpm.sock;
                    try_files $uri =404;
                }
            
                location ~* /\. {
                    deny all;
                }
            }
     6.
        后台默认登录用户
        用户名：system@admin.com
        初始密码为数据库密码
## Contributor
参与的小伙伴们

<a href="https://github.com/gsalpha">
    <img alt="" width="260" height="260" class="avatar width-full height-full avatar-before-user-status" src="https://avatars2.githubusercontent.com/u/21122282?s=460&amp;v=4">
</a>

<a href="https://github.com/flyflyhe">
    <img alt="" width="260" height="260" class="avatar width-full height-full rounded-2" src="https://avatars1.githubusercontent.com/u/11418176?s=460&amp;v=4">
</a>

### 联系我
    个人微信账号：O_Bin_Laden
