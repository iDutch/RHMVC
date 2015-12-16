<?php

return array(
    'basepath' => '', //Strip off subdirectories when needed.
    'routes' => array(
        '^/404' => array(
            'layout'    => 'errorpage.phtml',
            'content'   => array(
                array(
                    'controller'    => 'ErrorController',
                    'action'        => 'error404Action',
                    'params' => array(),
                ),
            ),
        ),
        '^/test' => array(
            'layout'    => 'main.phtml',
            'header'    => array(
                array(
                    'controller'    => 'MenuController',
                    'action'        => 'indexAction',
                    'params' => array(),
                ),
            ),
            'content'   => array(
                array(
                    'controller'    => 'NewsController',
                    'action'        => 'indexAction',
                    'params' => array(),
                ),
            ),
        ),
    ),
);
