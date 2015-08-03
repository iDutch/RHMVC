<?php
return array(
    'basepath' => '/RHMVC/docs', //Strip off subdirectories when needed.
    'routes' => array(
        '^/(?<platform>dating|webcams)/notification/(?<ext>php|js)' => array(
            'layout' => 'tool.phtml',
            'content'   => array(
                array(
                    'controller'    => 'ToolController',
                    'action'        => 'notificationAction',
                    'params' => array(
                        'platform' => 'dating',
                        'ext' => 'php',
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
