
## 疫情出入管理

苟利国家生死以，岂因祸福避趋之，很惭愧，就做了一点微小的工作

#### 用户端：
    扫码打开页面，不同地区（小区|酒店|村）对应不同的二维码，填写出入信息。
#### 上峰管控端：
    后台打开疫情出入统计后台，查看各个地区的出入登记情况，同时可以添加修改地区（小区|酒店|村）
    如果发生疫情，可在后台进行搜索排查用户出入信息，为疫情管控提供及时可靠的帮助
    
#### 部署:
     获取代码: git clone https://github.com/margina757/nCoV_monitoring  
     composer 安装依赖
     参考env_example 配置数据库地址
     php init 初始化 选择生产环境
     php yii migrate 生成数据表
     使用nginx 或者 apache 配置域名 以及指向对应入口文件
     前台frontend/web/index.php 
     后台backend/web/index.php
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
