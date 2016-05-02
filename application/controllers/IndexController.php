<?php

class DashBoardController extends AbstractController
{

    public function admin_indexAction()
    {
        $view = new View(__DIR__ . '/../../application/views/dashboard/admin_index.phtml');

        return $view->parse();
    }

}