<?php

return [
    'basepath' => '', //Strip off subdirectories when needed.
    'routes'   => [
        //404 route
        '/404'                                                                               => [
            'methods' => 'GET',
            'layout'  => 'empty.phtml',
            'content' => [
                    [
                    'controller' => 'ErrorController',
                    'action'     => 'error404Action',
                    'params'     => [],
                ],
            ],
        ],
        '/405'                                                                               => [
            'methods' => 'GET',
            'layout'  => 'empty.phtml',
            'content' => [
                    [
                    'controller' => 'ErrorController',
                    'action'     => 'error405Action',
                    'params'     => [],
                ],
            ],
        ],
        '((/)?(index)?)?'                                                                    => [
            'methods' => 'GET',
            'layout'  => 'bootstrap.phtml',
            'content' => [
                    [
                    'controller' => 'BlogController',
                    'action'     => 'indexAction',
                    'params'     => [],
                ],
            ],
            'sidebar' => [
                    [
                    'controller' => 'BlogController',
                    'action'     => 'showCategoryMenuAction',
                    'params'     => [],
                ]
            ]
        ],
        '(/index)?/article/(?<article_id>[0-9]+)'                                            => [
            'methods' => 'GET|POST',
            'layout'  => 'bootstrap.phtml',
            'content' => [
                    [
                    'controller' => 'BlogController',
                    'action'     => 'showArticleAction',
                    'params'     => [
                        'article_id' => null,
                    ],
                ],
            ],
            'sidebar' => [
                    [
                    'controller' => 'BlogController',
                    'action'     => 'showCategoryMenuAction',
                    'params'     => [],
                ]
            ]
        ],
        '/admin'                                                                             => [
            'methods' => 'GET',
            'layout'  => 'admin.phtml',
            'content' => [
                    [
                    'controller' => 'DashboardController',
                    'action'     => 'indexAction',
                    'params'     => [],
                ],
            ]
        ],
        '/admin/blog(/)?(?<handler>[a-z]+)?(/)?(?<action>[a-z]+)?(/)?(?<article_id>[0-9]+)?' => [
            'methods' => 'GET|POST',
            'layout'  => 'admin.phtml',
            'content' => [
                    [
                    'controller' => 'BlogController',
                    'action'     => 'adminAction',
                    'params'     => [
                        'handler'    => 'articles',
                        'action'     => null,
                        'article_id' => null
                    ],
                ],
            ],
            'sidebar' => [
                    [
                    'controller' => 'BlogController',
                    'action'     => 'showAdminMenuAction',
                    'params'     => [],
                ]
            ]
        ]
    ]
];
