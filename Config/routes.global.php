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
        '/404' => [
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
        '/405' => [
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
        '[/index]/' => [
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
        '[/index]/article/:article_id[0-9]+' => [
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
        '[/index]/archive/:year[0-9]{4}/:month[0-9]{1,2}' => [
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
        '[/index]/category/:category_id[0-9]+/(.*)' => [
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
        '/admin' => [
            'methods' => 'GET',
            'layout' => 'admin.phtml',
            'content' => [
                [
                    'controller' => 'DashboardController',
                    'action' => 'indexAction',
                    'params' => [],
                ],
            ]
        ],
        '/admin/login' => [
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
        '/admin/blog/[:handler[a-z]+/[:action[a-z]+/:item_id[0-9]+]}}' => [
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
            'sidebar' => [
                [
                    'controller' => 'BlogController',
                    'action' => 'showAdminMenuAction',
                    'params' => [],
                ]
            ]
        ],
        '/admin/blog(/)?(?<handler>[a-z]+)?(/)?(?<action>(delete))?(/)?(?<item_id>[0-9]+)?' => [
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
            'sidebar' => [
                [
                    'controller' => 'BlogController',
                    'action' => 'showAdminMenuAction',
                    'params' => [],
                ]
            ]
        ]
    ]
];
