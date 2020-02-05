<?php
return [
    [
        'label' => '登录账号管理',
        'permissions' => [
            [
                'label' => '管理员管理',
                'routes' => [
                    '/admin/admin-users/*',
                ]
            ],
            [
                'label' => '角色管理',
                'routes' => [
                    '/admin/admin-roles/*',
                ]
            ],
            [
                'label' => '权限管理',
                'routes' => [
                    '/admin/role-auth/*',
                ]
            ],
            [
                'label' => '操作管理',
                'routes' => [
                    '/admin/admin-operate-log/*',
                ]
            ],
            [
                'label' => '登录日志',
                'routes' => [
                    '/admin/admin-login-log/*',
                ]
            ],
        ],
    ],
];