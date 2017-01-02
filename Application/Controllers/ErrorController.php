<?php

namespace Application\Controllers;

use System\Core\RHMVC\AbstractController;
use System\Core\RHMVC\View;

class ErrorController extends AbstractController
{
    public function error404Action()
    {
        http_response_code(404);
        $view = new View('error/error.phtml');
        $view->setVars(array(
            'title' => '404: Page not found'
        ));
        return $view->parse();
    }

    public function error405Action()
    {
        http_response_code(405);
        $view = new View('error/error.phtml');
        $view->setVars(array(
            'title' => '405: Method not allowed'
        ));
        return $view->parse();
    }

    public function error418Action()
    {
        header("HTTP/1.1 418 I'm a teapot");
        http_response_code(418);
        $view = new View('error/error.phtml');
        $view->setVars(array(
            'title' => '418: I\'m a teapot...',
            'body' => 'The HTCPCP Server is a teapot. The responding entity MAY be short and stout.'
        ));
        return $view->parse();
    }

    public function error500Action()
    {
        http_response_code(500);
        $view = new View('error/error.phtml');
        $view->setVars(array(
            'title' => '500: Internal server error'
        ));
        return $view->parse();
    }
}
