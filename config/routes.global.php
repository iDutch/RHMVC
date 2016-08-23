<?php

return array(
    'basepath'  => '', //Strip off subdirectories when needed.
    'routes'    => array(
        //404 route
        '/404' => array(
            'methods'   => 'GET',
            'layout'    => 'errorpage.phtml',
            'content'   => array(
                array(
                    'controller'    => 'ErrorController',
                    'action'        => 'error404Action',
                    'params'        => array(),
                ),
            ),
        ),
        '/405' => array(
            'methods'   => 'GET',
            'layout'    => 'errorpage.phtml',
            'content'   => array(
                array(
                    'controller'    => 'ErrorController',
                    'action'        => 'error405Action',
                    'params'        => array(),
                ),
            ),
        ),

        '/sig.png'    => array(
            'methods'   => 'GET',
            'layout'    => null,
            'content'   => array(
                array(
                    'controller'    => 'DashboardController',
                    'action'        => 'signatureAction',
                    'params'        => array(),
                ),
            ),
        ),

        '/quin.png'    => array(
            'methods'   => 'GET',
            'layout'    => null,
            'content'   => array(
                array(
                    'controller'    => 'DashboardController',
                    'action'        => 'quinAction',
                    'params'        => array(),
                ),
            ),
        ),

        //Index
        '/admin/dashboard(/(index)?)?'    => array(
            'methods'   => 'GET',
            'layout'    => 'index.phtml',
            'header'    => array(
                array(
                    'controller'    => 'MenuController',
                    'action'        => 'staticAction',
                    'params'        => array(),
                ),
            ),
            'content'   => array(
                array(
                    'controller'    => 'DashboardController',
                    'action'        => 'admin_indexAction',
                    'params'        => array(),
                ),
            ),
        ),
        '/admin/login' => array(
            'methods'   => 'GET|POST',
            'layout'    => 'login.phtml',
            'header'    => array(
                array(
                    'controller'    => 'MenuController',
                    'action'        => 'staticAction',
                    'params'        => array(),
                ),
            ),
            'content'   => array(
                array(
                    'controller'    => 'UserController',
                    'action'        => 'admin_loginAction',
                    'params'        => array(),
                ),
            ),
        ),
        '/admin/logout' => array(
            'methods'   => 'GET',
            'layout'    => 'login.phtml',
            'header'    => array(
                array(
                    'controller'    => 'MenuController',
                    'action'        => 'staticAction',
                    'params'        => array(),
                ),
            ),
            'content'   => array(
                array(
                    'controller'    => 'UserController',
                    'action'        => 'admin_logoutAction',
                    'params'        => array(),
                ),
            ),
        ),

        //Article
        '/admin/article/list'    => array(
            'methods'   => 'GET',
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
                    'action'        => 'admin_readAction',
                    'params'        => array(),
                ),
            ),
        ),
        '/admin/article/add' => array(
            'methods'   => 'GET|POST',
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
            'methods'   => 'GET|POST',
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

        //Category
        '/admin/category/list'    => array(
            'methods'   => 'GET',
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
                    'action'        => 'admin_readAction',
                    'params'        => array(),
                ),
            ),
        ),
        '/admin/category/add'    => array(
            'methods'   => 'GET|POST',
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
            'methods'   => 'GET|POST',
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
        //User
        '/admin/user/list'    => array(
            'methods'   => 'GET',
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
                    'controller'    => 'UserController',
                    'action'        => 'admin_readAction',
                    'params'        => array(),
                ),
            ),
        ),
        '/admin/user/add'    => array(
            'methods'   => 'GET|POST',
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
                    'controller'    => 'UserController',
                    'action'        => 'admin_createAction',
                    'params'        => array(),
                ),
            ),
        ),
        '/admin/user/edit/(?<user_id>[0-9]+)'    => array(
            'methods'   => 'GET|POST',
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
                    'controller'    => 'UserController',
                    'action'        => 'admin_updateAction',
                    'params'        => array(
                        'user_id' => null,
                    ),
                ),
            ),
        ),

        //Group
        '/admin/group/list'    => array(
            'methods'   => 'GET',
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
                    'controller'    => 'GroupController',
                    'action'        => 'admin_readAction',
                    'params'        => array(),
                ),
            ),
        ),
        '/admin/group/add'    => array(
            'methods'   => 'GET|POST',
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
                    'controller'    => 'GroupController',
                    'action'        => 'admin_addAction',
                    'params'        => array(),
                ),
            ),
        ),
        '/admin/group/edit/(?<group_id>[0-9]+)'    => array(
            'methods'   => 'GET|POST',
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
                    'controller'    => 'GroupController',
                    'action'        => 'admin_editAction',
                    'params'        => array(
                        'group_id' => null,
                    ),
                ),
            ),
        ),

        //Non admin, to pagecontroller
        '(?<uri>.*$)?' => array(
            'methods'   => 'GET|POST',
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
