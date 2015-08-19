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
        '^/api/(?<platform>flirtplek|neukoproepjes|hobbysletten|relatie|islive)/getprofiles/(?<count>[0-9]+)(/(?<format>json|xml))?' => array(
            'layout' => 'empty.phtml',
            'content'   => array(
                array(
                    'controller'    => 'APIController',
                    'action'        => 'getProfilesAction',
                    'params' => array(
                        'platform'  => null,
                        'count'     => null,
                        'format'    => 'json',
                    ),
                ),
            ),
        ),
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
    ),
);
