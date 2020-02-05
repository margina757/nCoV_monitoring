疫情出入管理
===============================

苟利国家生死以，岂因祸福避趋之，做了一些微小的工作，很惭愧

**用户端**：
    扫码打开页面，不同地区（小区|酒店|村）对应不同的二维码，填写出入信息。
    
**上峰管控端**：
    后台打开疫情出入统计后台，查看各个地区的出入登记情况，同时可以添加修改地区（小区|酒店|村）
    **如果发生疫情，可在后台进行搜索排查用户出入信息，为疫情管控提供及时可靠的帮助**

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


