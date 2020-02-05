疫情出入管理
===============================

苟利国家生死以，岂因祸福避趋之，做了一些微小的工作，很惭愧

**用户端**：
    扫码打开页面，不同地区（小区|酒店|村）对应不同的二维码，填写出入信息。
    
**上峰管控端**：
    后台打开疫情出入统计后台，查看各个地区的出入登记情况，同时可以添加修改地区（小区|酒店|村）
    **如果发生疫情，可在后台进行搜索排查用户出入信息，为疫情管控提供及时可靠的帮助**



### 联系我
O_Bin_Laden

![个人微信](https://my-blog-to-use.oss-cn-beijing.aliyuncs.com/2019-7/wechat3.jpeg)

### Contributor

下面是笔主收集的一些对本仓库提过有价值的pr或者issue的朋友，人数较多，如果你也对本仓库提过不错的pr或者issue的话，你可以加我的微信与我联系。下面的排名不分先后！

<a href="https://github.com/gsalpha">
    <img alt="" width="260" height="260" class="avatar width-full height-full avatar-before-user-status" src="https://avatars2.githubusercontent.com/u/21122282?s=460&amp;v=4">
</a>



INIT
-------------------

```
/path/to/php-bin/php /path/to/yii2-admin/init  --env=Development --overwrite=All
```

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains net_bar configurations
    controllers/         contains Web controller classes
    models/              contains fronent-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
environments/            contains environment-based overrides
```


