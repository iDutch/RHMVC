<?php

return array(
    'basepath'  => '', //Strip off subdirectories when needed.
    'routes'    => array(
        //404 route
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

        //Index
        '/admin/index'    => array(
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
                    'controller'    => 'IndexController',
                    'action'        => 'admin_indexAction',
                    'params'        => array(),
                ),
            ),
        ),
        '/admin/login' => array(
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

        //Category
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
        //User
        '/admin/user/list'    => array(
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
                    'action'        => 'admin_indexAction',
                    'params'        => array(),
                ),
            ),
        ),
        '/admin/group/add'    => array(
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
        '/admin/group/edit/(?<user_id>[0-9]+)'    => array(
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
                        'user_id' => null,
                    ),
                ),
            ),
        ),

        //Non admin, to pagecontroller
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
