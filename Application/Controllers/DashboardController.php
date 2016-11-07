<?php

namespace Application\Controllers;

use System\Core\RHMVC\AbstractController;
use System\Core\RHMVC\View;

class DashboardController extends AbstractController
{

    public function indexAction()
    {
        /* @var $Comment Comment */
        $Comment = $this->loadModel('Comment');
        $view = new View('dashboard/index.phtml');
        $view->setVars([
            'latest_comments' => $Comment->find('all', ['limit' => 5, 'order' => 'post_date desc'])
        ]);

        return $view->parse();
    }

    public function categoryMenuAction()
    {
        /* @var $Category Category */
        $Category = $this->loadModel('Category');
        $view = new View('blog/category_menu.phtml');
        $view->setVars([
            'categories' => $Category->find('all')
        ]);

        return $view->parse();
    }

}
