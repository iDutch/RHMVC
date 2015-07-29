<?php

class SomeController extends AbstractController
{

    public function headerAction()
    {
        return 'Header';
    }

    public function contentAction($param_1, $param_2)
    {
        $view = new View(__DIR__ . '/../../application/views/some/content.phtml');
        $this->layout->setVars(array(
            'title' => 'Content',
        ));
        $view->setVars(array(
            'message' => 'Herrow!',
            'p1' => $param_1,
            'p2' => $param_2,
            'bar' => $this->invokeController('BarController', 'barAction'),
        ));
        return $view->parse();
    }

    public function sidebarAction()
    {
        $this->layout->setVars(array(
            //'title' => 'Side',
        ));
        return 'Sidebar';
    }

} 