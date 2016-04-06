<?php

return array(
    'basepath'  => '', //Strip off subdirectories when needed.
    'routes'    => array(
        '^/404' => array(
            'layout'    => 'errorpage.phtml',
            'content'   => array(
                array(
                    'controller'    => 'ErrorController',
                    'action'        => 'error404Action',
                    'params'        => array(),
                ),
            ),
        ),
        '^/admin/news/list$'    => array(
            'layout'    => 'main.phtml',
            'header'    => array(
                array(
                    'controller'    => 'MenuController',
                    'action'        => 'indexAction',
                    'params'        => array(),
                ),
            ),
            'content'   => array(
                array(
                    'controller'    => 'ArticleController',
                    'action'        => 'admin_indexAction',
                    'params'        => array(),
                ),
            ),
        ),
        '^/admin/news/edit/(?<news_id>[0-9]+)$' => array(
            'layout'    => 'main.phtml',
            'header'    => array(
                array(
                    'controller'    => 'MenuController',
                    'action'        => 'indexAction',
                    'params'        => array(),
                ),
            ),
            'content'   => array(
                array(
                    'controller'    => 'ArticleController',
                    'action'        => 'admin_editAction',
                    'params'        => array(
                        'news_id'   => null,
                    ),
                ),
            ),
        ),
        '^(?!admin).*$' => array(
            'layout'    => null,
            'content'   => array(
                array(
                    'controller'    => 'PageController',
                    'action'        => 'getPage',
                    'params'        => array(),
                ),
            ),
        ),
    ),
);
