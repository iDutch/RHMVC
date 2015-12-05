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
    ),
);
