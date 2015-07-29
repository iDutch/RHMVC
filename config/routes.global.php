<?php
return array(
    'basepath' => '', //Strip off subdirectories when needed.
    'routes' => array(
        '^/users/(?<param_1>[0-9]+)(/?(?<param_2>\w*))' => array(
            'layout'    => 'onepage.phtml',
            'header'    => array(
                array(
                    'controller'    => 'SomeController',
                    'action'        => 'headerAction',
                    'params' => array(),
                ),
            ),
            'content'   => array(
                array(
                    'controller'    => 'SomeController',
                    'action'        => 'contentAction',
                    'params' => array(
                        'param_1' => null,
                        'param_2' => 'Je bent zelf een param!',
                    ),
                ),
            ),
            'sidebar'   => array(
                array(
                    'controller'    => 'SomeController',
                    'action'        => 'sidebarAction',
                    'params' => array(),
                ),
            ),
        ),
        '/404' => array(
            'layout'    => 'errorpage.phtml',
            'content'   => array(
                array(
                    'controller'    => 'ErrorController',
                    'action'        => 'error404action',
                    'params' => array(),
                ),
            ),
        ),
    ),
);
