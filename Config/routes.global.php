<?php

/**
 * Regex based routes
 * Valid routes examples:
 * /home/:param[\w]+        One or more alphanummeric characters same as [a-zA-Z0-9_]
 * /home/:param[a-z]{5}
 * /home/:param[0-9]{1,5}
 *
 */

return [
    'basepath' => '', //Strip off subdirectories when needed.
    'routes' => [
        //404 route
        '404' => [
            'route' => '/404',
            'methods' => 'GET',
            'layout' => 'empty.phtml',
            'content' => [
                [
                    'controller' => 'ErrorController',
                    'action' => 'error404Action',
                    'params' => [],
                ],
            ],
        ],
        '405' => [
            'route' => '/405',
            'methods' => 'GET',
            'layout' => 'empty.phtml',
            'content' => [
                [
                    'controller' => 'ErrorController',
                    'action' => 'error405Action',
                    'params' => [],
                ],
            ],
        ],
        '418' => [ //See https://tools.ietf.org/html/rfc2324
            'route' => '/418',
            'methods' => 'GET',
            'layout' => 'empty.phtml',
            'content' => [
                [
                    'controller' => 'ErrorController',
                    'action' => 'error418Action',
                    'params' => [],
                ],
            ],
        ],
        '500' => [
            'route' => '/500',
            'methods' => 'GET',
            'layout' => 'empty.phtml',
            'content' => [
                [
                    'controller' => 'ErrorController',
                    'action' => 'error500Action',
                    'params' => [],
                ],
            ],
        ],
        'index' => [
            'route' => '(/)?',
            'methods' => 'GET',
            'layout' => 'bootstrap.phtml',
            'content' => [
                [
                    'controller' => 'BlogController',
                    'action' => 'indexAction',
                    'params' => [],
                ],
            ],
            'sidebar' => [
                [
                    'controller' => 'BlogController',
                    'action' => 'showCategoryMenuAction',
                    'params' => [],
                ],
                [
                    'controller' => 'BlogController',
                    'action' => 'showArchiveMenuAction',
                    'params' => [],
                ]
            ]
        ],
        'blog/article' => [
            'route' => '/article/(?<article_id>[0-9]+)',
            'methods' => 'GET|POST',
            'layout' => 'bootstrap.phtml',
            'content' => [
                [
                    'controller' => 'BlogController',
                    'action' => 'showArticleAction',
                    'params' => [
                        'article_id' => null,
                    ],
                ],
            ],
            'sidebar' => [
                [
                    'controller' => 'BlogController',
                    'action' => 'showCategoryMenuAction',
                    'params' => [],
                ],
                [
                    'controller' => 'BlogController',
                    'action' => 'showArchiveMenuAction',
                    'params' => [],
                ]
            ]
        ],
        'blog/archive' => [
            'route' => '/archive/(?<year>[0-9]{4})/(?<month>[0-9]{1,2})',
            'methods' => 'GET',
            'layout' => 'bootstrap.phtml',
            'content' => [
                [
                    'controller' => 'BlogController',
                    'action' => 'archiveAction',
                    'params' => [
                        'year' => null,
                        'month' => null
                    ],
                ],
            ],
            'sidebar' => [
                [
                    'controller' => 'BlogController',
                    'action' => 'showCategoryMenuAction',
                    'params' => [],
                ],
                [
                    'controller' => 'BlogController',
                    'action' => 'showArchiveMenuAction',
                    'params' => [],
                ]
            ]
        ],
        'blog/category' => [
            'route' => '/category/(?<category_id>[0-9]+)/(?<category_name>[\w-]+)',
            'methods' => 'GET',
            'layout' => 'bootstrap.phtml',
            'content' => [
                [
                    'controller' => 'BlogController',
                    'action' => 'categoryAction',
                    'params' => [
                        'category_id' => null
                    ],
                ],
            ],
            'sidebar' => [
                [
                    'controller' => 'BlogController',
                    'action' => 'showCategoryMenuAction',
                    'params' => [],
                ],
                [
                    'controller' => 'BlogController',
                    'action' => 'showArchiveMenuAction',
                    'params' => [],
                ]
            ]
        ],
        'admin/dashboard' => [
            'route' => '/admin',
            'methods' => 'GET',
            'layout' => 'admindashboard.phtml',
            'content' => [
                [
                    'controller' => 'DashboardController',
                    'action' => 'indexAction',
                    'params' => [],
                ],
            ]
        ],
        'admin/login' => [
            'route' => '/admin/login',
            'methods' => 'GET|POST',
            'layout' => 'login.phtml',
            'content' => [
                [
                    'controller' => 'UserController',
                    'action' => 'loginAction',
                    'params' => [],
                ],
            ],
        ],
        'admin/request-reset-password' => [
            'route' => '/admin/request-reset-password',
            'methods' => 'GET|POST',
            'layout' => 'login.phtml',
            'content' => [
                [
                    'controller' => 'UserController',
                    'action' => 'requestResetPasswordAction',
                    'params' => [],
                ],
            ],
        ],
        'admin/reset-password/token' => [
            'route' => '/admin/reset-password/(?<token>[\W\w]+)',
            'methods' => 'GET|POST',
            'layout' => 'login.phtml',
            'content' => [
                [
                    'controller' => 'UserController',
                    'action' => 'resetPasswordAction',
                    'params' => [
                        'token' => null
                    ],
                ],
            ],
        ],
        'admin/blog/add_edit' => [
            'route' => '/admin/blog(/)?(?<handler>[a-z]+)?(/)?(?<action>(add|edit))?(/)?(?<item_id>[0-9]+)?',
            'methods' => 'GET|POST',
            'layout' => 'admin.phtml',
            'content' => [
                [
                    'controller' => 'BlogController',
                    'action' => 'adminAction',
                    'params' => [
                        'handler' => 'articles',
                        'action' => null,
                        'item_id' => null,
                    ],
                ],
            ],
            'submenu' => [
                [
                    'controller' => 'BlogController',
                    'action' => 'showAdminMenuAction',
                    'params' => [
                        'handler' => 'articles',
                    ],
                ]
            ]
        ],
        'admin/blog/delete' => [
            'route' => '/admin/blog(/)?(?<handler>[a-z]+)?(/)?(?<action>(delete))?(/)?(?<item_id>[0-9]+)?',
            'methods' => 'POST',
            'layout' => 'admin.phtml',
            'content' => [
                [
                    'controller' => 'BlogController',
                    'action' => 'adminAction',
                    'params' => [
                        'handler' => 'articles',
                        'action' => null,
                        'item_id' => null,
                    ],
                ],
            ],
            'submenu' => [
                [
                    'controller' => 'BlogController',
                    'action' => 'showAdminMenuAction',
                    'params' => [],
                ]
            ]
        ]
    ]
];
