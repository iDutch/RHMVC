<?php

class MenuController extends AbstractController
{

    public function indexAction()
    {
        $view = new View(__DIR__ . '/../../application/views/menu/index.phtml');

        return $view->parse();
    }

    public function getMenu($root_id)
    {
        $MenuModel = $this->loadModel('MenuModel');
        $menu = $MenuModel->getFullMenu();

        $view = new View(__DIR__ . '/../../application/views/menu/index.phtml');
        $view->setVars(array('menu' => $menu));

        return $view->parse();
    }

}
