<?php
return array(
    'basepath' => '/RHMVC/docs', //Strip off subdirectories when needed.
    'routes' => array(
        '^/(?<product>dating|webcams)/notification/(?<platform>flirtplek|neukoproepjes|hobbysletten|relatie|islive)/(?<ext>php|js)' => array(
            'layout' => 'empty.phtml',
            'content'   => array(
                array(
                    'controller'    => 'ToolController',
                    'action'        => 'notificationAction',
                    'params' => array(
                        'product'   => null,
                        'platform'  => null,
                        'ext'       => 'php',
                    ),
                ),
            ),
        ),
        '/404' => array(
            'layout'    => 'errorpage.phtml',
            'content'   => array(
                array(
                    'controller'    => 'ErrorController',
                    'action'        => 'error404Action',
                    'params' => array(),
                ),
            ),
        ),
    ),
);
