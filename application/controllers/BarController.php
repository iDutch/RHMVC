<?php

class BarController extends AbstractController
{

    public function barAction()
    {
        $view = new View(__DIR__ . '/../../application/views/bar/bar.phtml');
        $view->setVars(array(
            'bar' => 'Bar!',
        ));
        return $view->parse();
    }

} 