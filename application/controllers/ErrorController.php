<?php

class ErrorController extends AbstractController
{

    public function error404Action()
    {
        http_response_code(404);
        $view = new View(__DIR__ . '/../../application/views/error/error.phtml');
        $view->setVars(array(
            $this->title => '404: Page not found'
        ));

        return $view->parse();
    }

} 