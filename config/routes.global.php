<?php

return array(
    'basepath'  => '', //Strip off subdirectories when needed.
    'routes'    => array(
        '/404' => array(
            'layout'    => 'errorpage.phtml',
            'content'   => array(
                array(
                    'controller'    => 'ErrorController',
                    'action'        => 'error404Action',
                    'params'        => array(),
                ),
            ),
        ),
        '/admin/article/list'    => array(
            'layout'    => 'main.phtml',
            'header'    => array(
                array(
                    'controller'    => 'MenuController',
                    'action'        => 'staticAction',
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
        '/admin/article/add' => array(
            'layout'    => 'main.phtml',
            'header'    => array(
                array(
                    'controller'    => 'MenuController',
                    'action'        => 'staticAction',
                    'params'        => array(),
                ),
            ),
            'content'   => array(
                array(
                    'controller'    => 'ArticleController',
                    'action'        => 'admin_addAction',
                    'params'        => array(),
                ),
            ),
        ),
        '/admin/article/edit/(?<article_id>[0-9]+)' => array(
            'layout'    => 'main.phtml',
            'header'    => array(
                array(
                    'controller'    => 'MenuController',
                    'action'        => 'staticAction',
                    'params'        => array(),
                ),
            ),
            'content'   => array(
                array(
                    'controller'    => 'ArticleController',
                    'action'        => 'admin_editAction',
                    'params'        => array(
                        'article_id'   => null,
                    ),
                ),
            ),
        ),
        '/admin/category/list'    => array(
            'layout'    => 'main.phtml',
            'header'    => array(
                array(
                    'controller'    => 'MenuController',
                    'action'        => 'staticAction',
                    'params'        => array(),
                ),
            ),
            'content'   => array(
                array(
                    'controller'    => 'CategoryController',
                    'action'        => 'admin_indexAction',
                    'params'        => array(),
                ),
            ),
        ),
        '/admin/category/add'    => array(
            'layout'    => 'main.phtml',
            'header'    => array(
                array(
                    'controller'    => 'MenuController',
                    'action'        => 'staticAction',
                    'params'        => array(),
                ),
            ),
            'content'   => array(
                array(
                    'controller'    => 'CategoryController',
                    'action'        => 'admin_addAction',
                    'params'        => array(),
                ),
            ),
        ),
        '/admin/category/edit/(?<category_id>[0-9]+)'    => array(
            'layout'    => 'main.phtml',
            'header'    => array(
                array(
                    'controller'    => 'MenuController',
                    'action'        => 'staticAction',
                    'params'        => array(),
                ),
            ),
            'content'   => array(
                array(
                    'controller'    => 'CategoryController',
                    'action'        => 'admin_editAction',
                    'params'        => array(
                        'category_id' => null,
                    ),
                ),
            ),
        ),
        '(?<uri>.*$)?' => array(
            'layout'    => null,
            'content'   => array(
                array(
                    'controller'    => 'PageController',
                    'action'        => 'getPage',
                    'params'        => array(
                        'uri'   => '/',
                    ),
                ),
            ),
        ),
    ),
);
