<?php

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
        //
        '((/)?(index)?)?' => [
            'methods' => 'GET|POST',
            'layout' => 'bootstrap.phtml',
            'sidebar' => [
                    [
                    'controller' => 'MenuController',
                    'action' => 'indexAction',
                    'params' => [],
                ],
            ],
            'content' => [
                    [
                    'controller' => 'DefaultController',
                    'action' => 'defaultAction',
                    'params' => [],
                ],
            ],
        ],
        '/blog(/)?' => [
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
                    'action' => 'categoryMenuAction',
                    'params' => [],
                ]
            ]
        ],
        '/blog/article/(?<article_id>[0-9]+)(/)?' => [
            'methods' => 'GET|POST',
            'layout' => 'bootstrap.phtml',
            'content' => [
                    [
                    'controller' => 'BlogController',
                    'action' => 'articleAction',
                    'params' => [
                        'article_id' => null
                    ],
                ],
            ],
            'sidebar' => [
                    [
                    'controller' => 'BlogController',
                    'action' => 'categoryMenuAction',
                    'params' => [],
                ]
            ]
        ]
    ],
];
