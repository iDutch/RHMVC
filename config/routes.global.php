<?php

return [
    'basepath'  => '', //Strip off subdirectories when needed.
    'routes'    => [
        //404 route
        '/404' => [
            'methods'   => 'GET',
            'layout'    => 'empty.phtml',
            'content'   => [
                [
                    'controller'    => 'ErrorController',
                    'action'        => 'error404Action',
                    'params'        => [],
                ],
            ],
        ],
        '/405' => [
            'methods'   => 'GET',
            'layout'    => 'empty.phtml',
            'content'   => [
                [
                    'controller'    => 'ErrorController',
                    'action'        => 'error405Action',
                    'params'        => [],
                ],
            ],
        ],

        //
        '((/)?(index)?)?' => [
            'methods'   => 'GET|POST',
            'layout'    => 'default.phtml',
            'content'   => [
                [
                    'controller'    => 'DefaultController',
                    'action'        => 'defaultAction',
                    'params'        => [],
                ],
            ],
        ],
    ],
];
